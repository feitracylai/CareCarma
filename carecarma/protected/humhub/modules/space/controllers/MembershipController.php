<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\space\controllers;

use humhub\modules\user\models\Contact;
use Yii;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\HttpException;
use humhub\modules\user\models\User;
use humhub\modules\space\models\Space;
use humhub\models\Setting;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\forms\RequestMembershipForm;
use humhub\modules\user\widgets\UserListBox;

/**
 * SpaceController is the main controller for spaces.
 *
 * It show the space itself and handles all related tasks like following or
 * memberships.
 *
 * @author Luke
 * @package humhub.modules_core.space.controllers
 * @since 0.5
 */
class MembershipController extends \humhub\modules\content\components\ContentContainerController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
            ]
        ];
    }

    /**
     * Provides a searchable user list of all workspace members in json.
     *
     */
    public function actionSearch()
    {
        Yii::$app->response->format = 'json';

        $space = $this->getSpace();

        if (!$space->isMember()) {
            throw new HttpException(404, Yii::t('SpaceModule.controllers_SpaceController', 'This action is only available for circle members!'));
        }

        $results = array();
        $keyword = Yii::$app->request->get('keyword');


        $query = User::find();
        $query->leftJoin('space_membership', 'space_membership.user_id=user.id AND space_membership.space_id=:space_id AND space_membership.status=:member', ['space_id' => $space->id, 'member' => Membership::STATUS_MEMBER]);
        $query->andWhere('space_membership.space_id IS NOT NULL');


        $query->joinWith('profile');
        $query->limit(10);

        // Build Search Condition
        $parts = explode(" ", $keyword);
        $i = 0;
        foreach ($parts as $part) {
            $i++;
            $query->andWhere("(user.email LIKE :match OR "
                    . "user.username LIKE :match OR "
                    . "profile.firstname LIKE :match OR "
                    . "profile.lastname LIKE :match OR "
                    . "profile.title LIKE :match)", ['match' => '%' . $part . '%']);
        }

        foreach ($query->all() as $user) {
            $userInfo['guid'] = $user->guid;
            $userInfo['displayName'] = \yii\helpers\Html::encode($user->displayName);
            $userInfo['email'] = $user->email;
            $userInfo['image'] = $user->getProfileImage()->getUrl();
            $userInfo['link'] = $user->getUrl();
            $results[] = $userInfo;
        }

        return $results;
    }

    /**
     * Requests Membership for this Space
     */
    public function actionRequestMembership()
    {
        $this->forcePostRequest();
        $space = $this->getSpace();

        if (!$space->canJoin(Yii::$app->user->id))
            throw new HttpException(500, Yii::t('SpaceModule.controllers_SpaceController', 'You are not allowed to join this circle!'));

        if ($space->join_policy == Space::JOIN_POLICY_APPLICATION) {
            // Redirect to Membership Request Form
            return $this->redirect($this->createUrl('//space/space/requestMembershipForm', array('sguid' => $this->getSpace()->guid)));
        }

        $space->addMember(Yii::$app->user->id);
        return $this->htmlRedirect($space->getUrl());
    }

    /**
     * Requests Membership Form for this Space
     * (If a message is required.)
     *
     */
    public function actionRequestMembershipForm()
    {
        $space = $this->getSpace();

        // Check if we have already some sort of membership
        if (Yii::$app->user->isGuest || $space->getMembership(Yii::$app->user->id) != null) {
            throw new HttpException(500, Yii::t('SpaceModule.controllers_SpaceController', 'Could not request membership!'));
        }

        $model = new RequestMembershipForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $space->requestMembership(Yii::$app->user->id, $model->message);
            return $this->renderAjax('requestMembershipSave');
        }

        return $this->renderAjax('requestMembership', ['model' => $model, 'space' => $space]);
    }

    /**
     * Revokes Membership for this workspace
     */
    public function actionRevokeMembership()
    {
        $this->forcePostRequest();
        $space = $this->getSpace();

        if ($space->isSpaceOwner()) {
            throw new HttpException(500, Yii::t('SpaceModule.controllers_SpaceController', 'As owner you cannot revoke your membership!'));
        }



        //send inviteDeclined notification
        $userId = Yii::$app->user->id;
        $user = User::findOne(['id' => $userId]);
        $membership = $space->getMembership($userId);


        $space->removeMember();

        if ($membership->originator == null){
            $admins = $space->getAdmins();
            foreach ($admins as $admin){
                $notification = new \humhub\modules\space\notifications\InviteDeclined();
                $notification->source = $space;
                $notification->originator = $user;
                $notification->send($admin);
            }
        } else {
            $notification = new \humhub\modules\space\notifications\InviteDeclined();
            $notification->source = $space;
            $notification->originator = $user;
            $notification->send($membership->originator);
        }




        if ($space->visibility == Space::VISIBILITY_NONE){
            return $this->redirect(Url::home());
        }

        //if user deny the invite, change to follow
        $space->follow();
        return $this->redirect($space->getUrl());
    }

    /**
     * Invite New Members to this workspace
     */
    public function actionInvite()
    {
        $space = $this->getSpace();
        $doit = (int)Yii::$app->request->get('doit');

        // Check Permissions to Invite
        if (!$space->canInvite()) {
            throw new HttpException(403, 'Access denied - You cannot invite members!');
        }



        $contacts = Contact::findAll(['user_id' => Yii::$app->user->id, 'linked' => 1]);
        $users = array();
//        $allUsers = User::findAll(['group_id' => 1]);
//        foreach ($allUsers as $user){
//            $contactUserId = $user->id;
//            if (!$space->isMember($contactUserId)){
//                $users[] = User::findOne(['id' => $contactUserId]);
//            }
//        }
        foreach ($contacts as $contact){
            $contactUserId = $contact->contact_user_id;
            if (!$space->isMember($contactUserId)){
                $users[] = User::findOne(['id' => $contactUserId]);
            }
        }

        if ($doit == 2) {

//            $statusInvite = false;
//            $guid = $_POST['data'];
            $user = User::findOne(['id' => Yii::$app->request->get('user_id')]);

            // Invite existing members

            $space->inviteMember($user->id, Yii::$app->user->id);
//            $statusInvite = $space->getMembership($user->id)->status;


//            return $this->renderAjax('statusInvite', array('status' => $statusInvite, 'space' => $space));
        }

        return $this->renderAjax('invite', array('space' => $space, 'users' => $users));
    }

    public function actionStatusInvite()
    {
        $statusInvite = false;

        $space = $this->getSpace();
        $contactUserId = Yii::$app->request->get('user_id');

//        $space->inviteMember($contactUserId, Yii::$app->user->id);
//        $statusInvite = $space->getMembership($contactUserId)->status;

        return $this->renderAjax('statusInvite', array('status' => $statusInvite, 'space' => $space));
    }



    /**
     * When a user clicks on the Accept Invite Link, this action is called.
     * After this the user should be member of this workspace.
     */
    public function actionInviteAccept()
    {

        $this->forcePostRequest();
        $space = $this->getSpace();

        // Load Pending Membership
        $membership = $space->getMembership();
        if ($membership == null) {
            throw new HttpException(404, Yii::t('SpaceModule.controllers_SpaceController', 'There is no pending invite!'));
        }

        // Check there are really an Invite
        if ($membership->status == Membership::STATUS_INVITED) {
            $space->addMember(Yii::$app->user->id);
        }

        return $this->redirect($space->getUrl());
    }

    /**
     * Toggle space content display at dashboard
     * 
     * @throws HttpException
     */
    public function actionSwitchDashboardDisplay()
    {
        $this->forcePostRequest();
        $space = $this->getSpace();

        // Load Pending Membership
        $membership = $space->getMembership();
        if ($membership == null) {
            throw new HttpException(404, 'Membership not found!');
        }

        if (Yii::$app->request->get('show') == 0) {
            $membership->show_at_dashboard = 0;
        } else {
            $membership->show_at_dashboard = 1;
        }
        $membership->save();

        return $this->redirect($space->getUrl());
    }

    /**
     * Returns an user list which are space members
     */
    public function actionMembersList()
    {
        $query = User::find();
        $query->join('LEFT JOIN', 'space_membership', 'space_membership.user_id=user.id');
        $query->andWhere(['space_membership.status' => Membership::STATUS_MEMBER]);
        $query->andWhere(['user.status' => User::STATUS_ENABLED]);
        $query->andWhere(['space_id' => $this->getSpace()->id]);
        $query->orderBy(['space_membership.group_id' => SORT_DESC]);

        $title = Yii::t('SpaceModule.controllers_MembershipController', "<strong>Members</strong>");

        return $this->renderAjaxContent(UserListBox::widget(['query' => $query, 'title' => $title]));
    }

}

?>
