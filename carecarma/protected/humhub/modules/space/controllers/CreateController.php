<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\space\controllers;

use humhub\modules\user\models\Contact;
use humhub\modules\user\models\User;
use Yii;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\HttpException;
use humhub\components\Controller;
use humhub\modules\space\models\Space;
use humhub\models\Setting;
use humhub\modules\space\permissions\CreatePublicSpace;
use humhub\modules\space\permissions\CreatePrivateSpace;

/**
 * CreateController is responsible for creation of new spaces
 *
 * @author Luke
 * @package humhub.modules_core.space.controllers
 * @since 0.5
 */
class CreateController extends Controller
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

    public function actionIndex()
    {
        return $this->redirect(Url::to(['create']));
    }

    /**
     * Creates a new Space
     */
    public function actionCreate()
    {
        // User cannot create spaces (public or private)
        if (!Yii::$app->user->permissionmanager->can(new CreatePublicSpace) && !Yii::$app->user->permissionmanager->can(new CreatePrivateSpace())) {
            throw new HttpException(400, 'You are not allowed to create circles!');
        }

        $model = $this->createSpaceModel();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return $this->actionInvite($model->id);
        }

        $visibilityOptions = [];
        if (Setting::Get('allowGuestAccess', 'authentication_internal') && Yii::$app->user->permissionmanager->can(new CreatePublicSpace)) {
            $visibilityOptions[Space::VISIBILITY_ALL] = Yii::t('SpaceModule.base', 'Public (Users & Guests)');
        }
        if (Yii::$app->user->permissionmanager->can(new CreatePublicSpace)) {
            $visibilityOptions[Space::VISIBILITY_REGISTERED_ONLY] = Yii::t('SpaceModule.base', 'Public (Visible)');
        }
        if (Yii::$app->user->permissionmanager->can(new CreatePrivateSpace())) {
            $visibilityOptions[Space::VISIBILITY_NONE] = Yii::t('SpaceModule.base', 'Private (Invisible)');
        }
        
        $joinPolicyOptions = [
            Space::JOIN_POLICY_NONE => Yii::t('SpaceModule.base', 'Only by invite'),
            Space::JOIN_POLICY_APPLICATION => Yii::t('SpaceModule.base', 'Invite and request'),
//            Space::JOIN_POLICY_FREE => Yii::t('SpaceModule.base', 'Everyone can enter')
        ];

        return $this->renderAjax('create', ['model' => $model, 'visibilityOptions' => $visibilityOptions, 'joinPolicyOptions' => $joinPolicyOptions]);
    }

    /**
     * Activate / deactivate modules
     */
    public function actionModules($space_id)
    {
        $space = Space::find()->where(['id' => $space_id])->one();

        if (count($space->getAvailableModules()) == 0) {

            $model = new \humhub\modules\space\models\forms\InviteForm();
            $model->space = $space;



            return $this->renderAjax('invite', ['spaceId' => $space->id, 'model' => $model, 'space' => $space]);
        } else {
            return $this->renderAjax('modules', ['space' => $space, 'availableModules' => $space->getAvailableModules()]);
        }
    }

    /**
     * Invite user
     */
    public function actionInvite($space_id)
    {
        $space = Space::find()->where(['id' => $space_id])->one();

        $contacts = Contact::findAll(['user_id' => Yii::$app->user->id, 'linked' => 1]);
        $users = array();

        foreach ($contacts as $contact){
            $contactUserId = $contact->contact_user_id;
            if (!$contactUserId) {
                $users[] = User::findOne(['id' => $contactUserId]);
            }

        }

//        Yii::getLogger()->log($users, Logger::LEVEL_INFO, 'MyLog');
        return $this->renderAjax('invite', array('users' => $users, 'space' => $space, 'space_id' => $space_id));
    }

    public function actionInviteMember(){
        $space_id = Yii::$app->request->get('space_id');
        $space = Space::find()->where(['id' => $space_id])->one();

        $user = User::findOne(['id' => Yii::$app->request->get('user_id')]);
        $space->inviteMember($user->id, Yii::$app->user->id);


        return $this->actionInvite($space_id);
    }

    /**
     * Cancel Space creation
     */
    public function actionCancel()
    {

        $space = Space::find()->where(['id' => Yii::$app->request->get('spaceId', "")])->one();
        $space->delete();
    }

    /**
     * Creates an empty space model
     * 
     * @return Space
     */
    protected function createSpaceModel()
    {
        $model = new Space();
        $model->scenario = 'create';
        $model->visibility = Setting::Get('defaultVisibility', 'space');
        $model->join_policy = Setting::Get('defaultJoinPolicy', 'space');
        return $model;
    }

}

?>
