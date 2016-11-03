<?php
namespace humhub\modules\user\controllers;

use humhub\models\Setting;
use humhub\modules\directory\controllers\DirectoryController;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\forms\SecuritySetting;
use humhub\modules\user\models\Invite;
use humhub\modules\user\models\ProfileField;
use humhub\modules\user\models\Profile;
use humhub\modules\user\notifications\AddContact;
use humhub\modules\user\notifications\LinkRemove;
use Yii;
use yii\helpers\Url;
use humhub\compat\HForm;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\User;
use humhub\modules\user\models\ContactSearch;
use yii\log\Logger;
use \humhub\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\helpers\BaseJson;
use humhub\modules\user\models\Device;
use humhub\modules\user\models\Localcontact;
use humhub\modules\user\models\ContactInfo;
use humhub\libs\GCM;


/**
 * ContactController implements the CRUD actions for contact model.
 * @property mixed humhub
 */
class ContactController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public $subLayout = "@humhub/modules/user/views/contact/_layout";
    /**
     * Lists all contact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $id = Yii::$app->user->id;

        $user = User::findOne(['id' => $id]);
        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        $spaces = array();
        $members = Membership::findAll(['user_id' => $user->id]);
        if ($members != null){
            foreach ($members as $member){
                $space = Space::findOne(['id' => $member->space_id]);
                $spaces[] = $space;
            }
        }

//        if (Yii::$app->request->post('dropDownColumnSubmit')){
//            Yii::$app->response->format = 'json';
//            $submitSpace = $spaces[Yii::$app->request->post('circle')];
//            $contact_user_id = Yii::$app->request->post('contact_user_id');
//            $submitSpace->inviteMember($contact_user_id, $user->id);
//
////            $this->redirect($submitSpace->createUrl('/space/membership/status-invite', ['user_id' => 2]));
//        }

		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user,
            'spaces' => $spaces,
        ]);
    }

    public function actionView()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id, 'user_id' => $user->id]);
        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'PEOPLE not found!'));
        }
        return $this->render('view', array(
            'contact' => $contact,
            'user' => $user
        ));
    }

    public function actionCircleInvite()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);
        Yii::getLogger()->log($user->id, Logger::LEVEL_ERROR, 'MyLog');
        $contact_user_id = Yii::$app->request->get('cuid');
        $doit = (int)Yii::$app->request->get('doit');

        $spacesInvite = array();
        $members = Membership::findAll(['user_id' => $user->id]);
        if ($members != null){
            foreach ($members as $member){
                $space = Space::findOne(['id' => $member->space_id]);
                if (!$space->isMember($contact_user_id)){
                    $spacesInvite[] = $space;
                }

            }
        }

        if ($doit == 2)
        {
            $spaceId = Yii::$app->request->get('space_id');
            $inviteSpace = Space::findOne(['id' => $spaceId]);
            $inviteSpace->inviteMember($contact_user_id, $user->id);

        } elseif ($doit == 3) {
            //Remove circle invite
            $removeSpace = Space::findOne(['id' => Yii::$app->request->get('space_id')]);
            if ($removeSpace->isSpaceOwner($contact_user_id)) {
                throw new HttpException(500, 'Owner cannot be removed!');
            }

            $removeSpace->removeMember($contact_user_id);
        }


        return $this->renderAjax('circle-invite', array(
            'spacesInvite' => $spacesInvite,
            'cuid' => $contact_user_id,
            'user' => $user,
        ));
    }
    

    public function actionEdit()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('id'), 'user_id' => $user->id]);
        $contact->scenario = 'editContact';
        if ($contact == null)
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'PEOPLE not found!'));
        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();
        // Add User Form
        $definition['elements']['Contact'] = array(
            'type' => 'form',
            'elements' => array(
                'contact_first' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'readonly' => 'true',
                ),
                'contact_last' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'readonly' => 'true',
                ),
                'nickname' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'relation' => array(
                    'type' => 'dropdownlist',
                    'class' => 'form-control',
                    'items' => Yii::$app->params['availableRelationship'],
                ),
                'contact_mobile' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'device_phone' =>array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'readonly' => 'true',
                ),
                'home_phone' =>array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'work_phone' =>array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'contact_email' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'watch_primary_number' => array(
                    'type' => 'checkbox',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'phone_primary_number' => array(
                    'type' => 'checkbox',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
            ),
        );
        // Get Form Definition
        $definition['buttons'] = array(
            'delete' => array(
                'type' => 'submit',
                'label' => Yii::t('UserModule.controllers_ContactController', 'Delete'),
                'class' => 'btn btn-danger ',
            ),
            'save' => array(
                'type' => 'submit',
                'label' => Yii::t('UserModule.controllers_ContactController', 'Save'),
                'class' => 'btn btn-primary pull-right',
            ),
        );
        $form = new HForm($definition);
        $form->models['Contact'] = $contact;
        if ($form->submitted('save') && $form->validate()) {
            if ($form->save()) {
               // $user = User::findOne(['id' => $contact->user_id]);

//                $contact->notifyDevice('update');
                $gcm = new GCM();
                $device_id = $user->device_id;
                $device = Device::findOne(['device_id' => $device_id]);
                $data = array();
                $data['type'] = 'contact,updated';
                $gcm_id = $device->gcmId;
                $gcm->send($gcm_id, $data);
                return $this->redirect(Url::toRoute('/user/contact'));
            }
        }
        if ($form->submitted('delete')) {
            return $this->redirect($user->createUrl('/user/contact/delete',[ 'id' => $contact->contact_id]));
        }
        return $this->render('edit', array('hForm' => $form, 'contact' => $contact, 'user' => $user));
    }

    public function actionAdd()
    {
        $thisUser = User::findOne(['guid' => Yii::$app->user->guid]);
        $contactUser = User::findOne(['id' => Yii::$app->request->get('connect_id')]);
        $doit = (int) Yii::$app->request->get('doit');

//        $users = User::findAll(['status'=> 1]);


        $empty = false;
        $keyword = Yii::$app->request->get('keyword', "");
        if ($keyword == "")
            $empty = true;

        $page = (int) Yii::$app->request->get('page', 1);
        $searchOptions = [
            'model' => \humhub\modules\user\models\User::className(),
            'page' => $page,
            'pageSize' => Setting::Get('paginationSize'),
//            'limitUsers' => $contacts,
        ];
        $searchResultSet = Yii::$app->search->find($keyword, $searchOptions);

        $users = $searchResultSet->getResultInstances();
        $thisUserMember = Membership::findAll(['user_id' => $thisUser->id]);
        $contacts = array();
        $spaces = array();
        foreach ($users as $user){
            $existContact = Contact::findAll(['user_id' => $thisUser->id, 'contact_user_id' => $user->id, 'linked' => 1]);
            if ($user->id != $thisUser->id && !$existContact){
                if ($thisUserMember != null){
                    $isSameSpace = false;
                    foreach ($thisUserMember as $s){
                        $m = Membership::findOne(['space_id' => $s->space_id, 'user_id' => $user->id]);
                        if ($m != null){
                            $isSameSpace = true;
//                            array_unshift($spaces, Space::findOne(['id' => $s->space_id]));
                            $spaces[$user->id] = Space::findOne(['id' => $s->space_id]);
                            break;
                        }
                    }
                    if ($isSameSpace){
                        array_unshift($contacts, $user);
                    } else {
                        array_push($contacts, $user);
                    }

                } else {
                    array_push($contacts, $user);
                }


            }
        }

        $pagination = new \yii\data\Pagination(['totalCount' => count($contacts)]);
//        Yii::getLogger()->log([count($contacts), $searchResultSet->pageSize], Logger::LEVEL_INFO, 'MyLog');

        if ($doit == 2){
            $needNotify = true;
            $privacy = $contactUser->getSetting("contact_notify_setting", 'contact', \humhub\models\Setting::Get('contact_notify_setting', 'send'));
            if ($privacy == User::CONTACT_NOTIFY_NOONE) {
                $needNotify = false;
            } elseif ($privacy == User::CONTACT_NOTIFY_NOCIRCLE){
                $membershipSpaces = Membership::findAll(['user_id' => $contactUser->id]);
                if ($membershipSpaces != null){
                    foreach ($membershipSpaces as $membershipSpace){
                        $userMemeber = Membership::findOne(['space_id' => $membershipSpace->space_id, 'user_id' => $thisUser->id]);
                        if($userMemeber != null){
                            $needNotify = false;
                            break;
                        }
                    }
                }
            }

            if ($needNotify == true){
                $contact = Contact::findOne(['user_id' => $thisUser->id, 'contact_user_id' => $contactUser->id]);
                if ($contact == null){
                    $contact = new Contact();
                }
                $contact->sendLink($contactUser, $thisUser);
            } else {
                //User add contact
                $userContact = Contact::findOne(['user_id' => $thisUser->id, 'contact_user_id' => $contactUser->id]);
                if ($userContact == null){
                    $userContact = new Contact();
                    $userContact->user_id = $thisUser->id;
                    $userContact->contact_user_id = $contactUser->id;
                }
                $userContact->contact_first = $contactUser->profile->firstname;
                $userContact->contact_last = $contactUser->profile->lastname;
                $userContact->contact_email = $contactUser->email;
                $userContact->linked = 1;
                $userContact->home_phone = $contactUser->profile->phone_private;
                $userContact->work_phone = $contactUser->profile->phone_work;
                if ($contactUser->device_id != null)
                {
                    $userContact->device_phone = $contactUser->device->phone;
                }
                $userContact->save();
//                $userContact->notifyDevice('add');

                $notification = new AddContact();
                $notification->source = $userContact;
                $notification->originator = $thisUser;
                $notification->send($contactUser);

                //contact user add contact
                $newContact = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $thisUser->id]);
                if ($newContact == null){
                    $newContact = new Contact();
                    $newContact->user_id = $contactUser->id;
                    $newContact->contact_user_id = $thisUser->id;
                }
                $newContact->contact_first = $thisUser->profile->firstname;
                $newContact->contact_last = $thisUser->profile->lastname;
                $newContact->contact_mobile = $thisUser->profile->mobile;
                $newContact->contact_email = $thisUser->email;
                $newContact->linked = 1;
                $newContact->home_phone = $thisUser->profile->phone_private;
                $newContact->work_phone = $thisUser->profile->phone_work;
                if ($thisUser->device_id != null)
                {
                    $newContact->device_phone = $thisUser->device->phone;
                }
                $newContact->save();
//                $newContact->notifyDevice('add');

                $gcm = new GCM();
                $device_id = $thisUser->device_id;
                $device = Device::findOne(['device_id' => $device_id]);
                $data = array();
                $data['type'] = 'contact,updated';
                Yii::getLogger()->log($data, Logger::LEVEL_INFO, 'MyLog');
                $gcm_id = $device->gcmId;
                $gcm->send($gcm_id, $data);

                $gcm = new GCM();
                $user = User::findOne(['user_id' => $contactUser->id]);
                $device_id = $user->device_id;
                $device = Device::findOne(['device_id' => $device_id]);
                $data = array();
                $data['type'] = 'contact,updated';
                Yii::getLogger()->log($data, Logger::LEVEL_INFO, 'MyLog');
                $gcm_id = $device->gcmId;
                $gcm->send($gcm_id, $data);
            }

//            Yii::getLogger()->log([$privacy, User::CONTACT_NOTIFY_EVERYONE], Logger::LEVEL_INFO, 'MyLog');

            return $this->redirect($thisUser->createUrl('add'));
        }

        return $this->render('add', array(
            'keyword' => $keyword,
            'users' => $contacts,
            'details' => $spaces,
            'pagination' => $pagination,
            'thisUser' => $thisUser,
            'empty' => $empty
        ));
    }

    public function actionConsole()
    {
        $id = Yii::$app->user->id;
        $searchModel = new ContactSearch();
        $searchModel->status = 'console';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);



        return $this->render('console', array(
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => User::findOne(['id' => $id]),
        ));
    }

    public function actionCreate()
    {
        $contactModel = new Contact();
        $contactModel->scenario = 'editContact';
        $contactModel->user_id = Yii::$app->user->id;
        $page = (int) Yii::$app->request->get('page', 1);
        $keyword = Yii::$app->request->get('keyword', "");
        $searchOptions = [
            'model' => \humhub\modules\user\models\User::className(),
            'page' => $page,
        ];
        $searchResultSet = Yii::$app->search->find($keyword, $searchOptions);
        $pagination = new \yii\data\Pagination(['totalCount' => $searchResultSet->total, 'pageSize' => $searchResultSet->pageSize]);
        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();
        // Add User Form
        $definition['elements']['Contact'] = array(
            'type' => 'form',
            'title' => Yii::t('UserModule.controllers_ContactController', 'New Contact'),
            'elements' => array(
                'contact_first' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'contact_last' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'nickname' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'relation' => array(
                    'type' => 'dropdownlist',
                    'class' => 'form-control',
                    'items' => Yii::$app->params['availableRelationship'],
                ),
                'contact_mobile' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'home_phone' =>array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'work_phone' =>array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'contact_email' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'watch_primary_number' => array(
                    'type' => 'checkbox',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'phone_primary_number' => array(
                    'type' => 'checkbox',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
            ),
        );
        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'label' => Yii::t('UserModule.controllers_ContactController', 'Add'),
            ),
        );
        $form = new HForm($definition);
//        $contactModel->relation = " ";
        $form->models['Contact'] = $contactModel;
        if ($form->submitted('save') && $form->validate()) {
//            $this->forcePostRequest();
//            $form->models['Contact']->status = User::STATUS_ENABLED;
            if ($form->models['Contact']->save()) {

                $contactModel->notifyDevice('add');

                return $this->redirect(Url::to(['index']));
            }
        }
        return $this->render('create', array(
            'hForm' => $form,
            'keyword' => $keyword,
            'users' => $searchResultSet->getResultInstances(),
            'pagination' => $pagination
        ));
    }

    public function actionLinkCancel()
    {
//        Yii::getLogger()->log(Yii::$app->request->get('id'), Logger::LEVEL_INFO, 'MyLog');
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('id')]);
        if ($contact != null) {
            $contact->CancelLink($user);
        }


        return $this->redirect(Url::to(['add']));
    }


    /**
     * Deletes a user permanently
     */
    public function actionDelete()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $id = (int) Yii::$app->request->get('id');
        $doit = (int) Yii::$app->request->get('doit');
        $contact = Contact::findOne(['contact_id' => $id, 'user_id' => $user->id]);
        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'PEOPLE not found!'));
        }
        if ($doit == 2) {
            if ($contact->contact_user_id != null){
                //delete the opposite contact
                $oppContact = Contact::findOne(['user_id' => $contact->contact_user_id, 'contact_user_id' => $user->id]);
                if ($oppContact != null){
                    $oppContact->delete();
                }
            }

            $contact->delete();
           // $user = User::findOne(['id' => $contact->user_id]);
            $contact->notifyDevice('delete');

            $gcm = new GCM();
            $device_id = $user->device_id;
            $device = Device::findOne(['device_id' => $device_id]);
            $data = array();
            $data['type'] = 'contact,updated';
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $data);

            return $this->redirect(Url::toRoute('index'));
        }
        return $this->render('delete', array('model' => $contact, 'user' => $user));
    }

    public function actionInvite()
    {

        $model = new \humhub\modules\user\models\forms\Invite;

//        Yii::getLogger()->log(print_r(Yii::$app->request->get(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $data = Yii::$app->request->get();
//        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        if (array_key_exists('googleemail',$data)) {
            $email = $data['googleemail'];
            $userInvite = Invite::findOne(['email' => $email]);
            if($userInvite === null){
                $userInvite = new Invite();
                $userInvite->email = $email;
            }

            $userInvite->source = Invite::SOURCE_CONTACT;
            $userInvite->user_originator_id = Yii::$app->user->id;
            $userInvite->space_invite_id = null;
            $userInvite->save();
            $userInvite->sendInviteMail();
            return $this->renderAjax('invite-success');

        }


        else if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->getEmails() as $email) {
                $userInvite = Invite::findOne(['email' => $email]);
                if($userInvite === null){
                    $userInvite = new Invite();
                    $userInvite->email = $email;
                }


                $userInvite->source = Invite::SOURCE_CONTACT;
                $userInvite->user_originator_id = Yii::$app->user->id;
                $userInvite->space_invite_id = null;
                $userInvite->save();
                $userInvite->sendInviteMail();
            }

            return $this->renderAjax('invite-success');
        }

        return $this->renderAjax('invite', array('model' => $model));
    }

//    public function actionImport()
//    {
//
//
//        return $this->render('import', array(
//
//        ));
//    }


    public function actionConnect()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $doit = (int) Yii::$app->request->get('doit');
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id, 'user_id' => $user->id]);
        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'PEOPLE not found!'));
        }
        $userSpaces = Membership::findAll(['user_id' => $user->id, 'status' => 3]);
        $users = array();
        $spaces = array();
        foreach ($userSpaces as $space){
            if ($space !== null)
            {
                $spaceId = $space->space_id;
                foreach (Membership::find()->where(['space_id' => $spaceId])->each() as $spaceContact){
                    $userId = $spaceContact->user_id;
                    $existContact = Contact::findOne(['user_id' => Yii::$app->user->id, 'contact_user_id' => $userId]);
                    if ($userId != Yii::$app->user->id && !$existContact && $spaceContact->status == 3){
                        $users[] = User::findOne(['id' => $userId]);
                        $spaces[$userId] = $spaceId;
                    }
                }
            }
        }


        $profiles = Profile::findAll(['mobile' => $contact->contact_mobile]);
        foreach ($profiles as $userProfile) {
            $userId =  $userProfile->user_id;
            $users[] = User::findOne(['id' => $userId]);
            $spaces[$userId] = 0;
        }
        $keyword = Yii::$app->request->get('keyword', "");
        $page = (int) Yii::$app->request->get('page', 1);
        $searchOptions = [
            'model' => \humhub\modules\user\models\User::className(),
            'page' => $page,
            'limitUsers' => $users,
        ];
        $searchResultSet = Yii::$app->search->find($keyword, $searchOptions);
        $pagination = new \yii\data\Pagination(['totalCount' => $searchResultSet->total, 'pageSize' => $searchResultSet->pageSize]);
        $connect_user_id = (int) Yii::$app->request->get('connect_id');
        if ($doit == 2) {
            $contact_user = User::findOne(['id' => $connect_user_id]);

//            $contact->contact_user_id = $connect_user_id;
//            $contact->contact_first = $contact_user->profile->firstname;
//            $contact->contact_last = $contact_user->profile->lastname;
//            $contact->contact_mobile = $contact_user->profile->mobile;
//            $contact->home_phone = $contact_user->profile->phone_private;
//            $contact->work_phone = $contact_user->profile->phone_work;
//            $contact->contact_email = $contact_user->email;
//            if ($contact_user->device_id != null)
//            {
//                $contact->device_phone = $contact_user->device->phone;
//            }
//            $contact->save();
//
//            $contact->notifyDevice('update');

            $contact->sendLink($contact_user, $user);

            return $this->redirect($user->createUrl('/user/contact/edit', ['id' => $contact->contact_id]));
        }
        return $this->render('connect', array(
           'keyword' => $keyword,
            'users' => $searchResultSet->getResultInstances(),
            'pagination' => $pagination,
            'contact' => $contact,
            'details' => $spaces,
            'connnect_id' => $connect_user_id,
            'thisUser' => $user,
        ));
    }

    public function actionLinkAccept ()
    {
        $contactUser = User::findOne(['id' => Yii::$app->user->id]);

        $user = User::findOne(['guid' => Yii::$app->request->get('uguid')]);
        $contact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);


        if ($contact != null) {
            $contact->LinkUser($contactUser, $user);
        }


        return $this->redirect(Url::to(['index']));
    }

    public function actionLinkDecline ()
    {
        $contactUser = User::findOne(['id' => Yii::$app->user->id]);

        $user = User::findOne(['guid' => Yii::$app->request->get('uguid')]);
        $contact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);

        if ($contact != null) {
            $contact->DenyLink($contactUser, $user);
        }

        return $this->redirect(Url::to(['index']));
    }

    public function actionDisconnect ()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id, 'user_id' => $user->id]);
        if ($contact != null) {
            $contact->contact_user_id = null;
            $contact->save();

            $contact->notifyDevice('update');



        }
        return $this->redirect($user->createUrl('edit', ['id' => $id]));
    }

    public function actionDeviceallcontact ()
    {
//        $user_id = Yii::$app->user->id;
//        Yii::getLogger()->log(print_r($user_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $data = Yii::$app->request->post();
        $device_id = $data['device_id'];
        $user = User::findOne(['username' => $data['username']]);
        $user_id = $user->id;
//        $contact = Contact::find()->where(['user_id' => $user_id])->all();
        $contact_list = array();
        $contact_list['type'] = 'contact,all';
        $contact_data = array();
        foreach (Contact::find()->where(['user_id' => $user_id])->each() as $contact) {
            $contactInfo = new ContactInfo();
            $contactInfo->contact_id = $contact->contact_id;
            $contactInfo->user_id = $user_id;
            $contactInfo->contact_user_id = $contact->contact_user_id;
            $contactInfo->contact_first = $contact->contact_first;
            $contactInfo->contact_last = $contact->contact_last;
            $contactInfo->nickname = $contact->nickname;
            $contactInfo->relation = $contact->relation;
            $contactInfo->contact_mobile = $contact->contact_mobile;
            $contactInfo->contact_email = $contact->contact_email;
            $contactInfo->home_phone = $contact->home_phone;
            $contactInfo->work_phone = $contact->work_phone;

            $contact_user = User::findOne(['id' => $contact->contact_user_id]);
            if ($contact_user) {
                $profileImage = new \humhub\libs\ProfileImage($contact_user->guid);
                $pos = strpos($profileImage->getUrl(), "?m=");
                $image = substr($profileImage->getUrl(), 0, $pos);
                $contactInfo->photo = $image;
            }
            array_push($contact_data, $contactInfo);
//            Yii::getLogger()->log(print_r(json_encode($contact->getAttributes(array('user_id', 'contact_user_id', 'nickname'))),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        }
        $contact_list['data'] = $contact_data;

        Yii::getLogger()->log(print_r($contact_list, true),yii\log\Logger::LEVEL_INFO,'MyLog');


        $gcm = new GCM();
        $device = Device::findOne(['device_id' => $device_id]);

        $gcm_id = $device->gcmId;
        $gcm->send($gcm_id, $contact_list);

//        $contact = Contact::find()->where(['user_id' => $user_id])->all();
//        Yii::getLogger()->log(print_r(CJSON::encode(convertModelToArray($contact)),true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        foreach ($contact_list->each() as $contact_user) {
//            $contact_user;
//        }
    }


    public function actionWatchallcontact ()
    {

        $data = Yii::$app->request->post();
        $device_id = $data['device_id'];
        $user = User::findOne(['username' => $data['username']]);
        $user_id = $user->id;

        $contact_list = array();
        $contact_list['type'] = 'watch,all';
        $contact_data = array();

        foreach (Contact::find()->where(['user_id' => $user_id])->each() as $contact) {
            Yii::getLogger()->log(print_r($contact,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            if ($contact->watch_primary_number == 1) {
                $contactInfo = new ContactInfo();
                $contactInfo->contact_id = $contact->contact_id;
                $contactInfo->user_id = $user_id;
                $contactInfo->contact_user_id = $contact->contact_user_id;
                $contactInfo->contact_first = $contact->contact_first;
                $contactInfo->contact_last = $contact->contact_last;
                $contactInfo->nickname = $contact->nickname;
                $contactInfo->relation = $contact->relation;
                $contactInfo->contact_mobile = $contact->contact_mobile;
                $contactInfo->contact_email = $contact->contact_email;
                $contactInfo->home_phone = $contact->home_phone;
                $contactInfo->work_phone = $contact->work_phone;

                $contact_user = User::findOne(['id' => $contact->contact_user_id]);
                if ($contact_user) {
                    $profileImage = new \humhub\libs\ProfileImage($contact_user->guid);
                    $pos = strpos($profileImage->getUrl(), "?m=");
                    $image = substr($profileImage->getUrl(), 0, $pos);
                    $contactInfo->photo = $image;
                }

                array_push($contact_data, $contactInfo);
            }
        }
        $contact_list['data'] = $contact_data;

        $gcm = new GCM();
        $device = Device::findOne(['device_id' => $device_id]);

        $gcm_id = $device->gcmId;
        Yii::getLogger()->log(print_r($contact_list,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm->send($gcm_id, $contact_list);
    }

    public function actionPhoneallcontact ()
    {
        $data = Yii::$app->request->post();
        $device_id = $data['device_id'];

        $user = User::findOne(['username' => $data['username']]);
        $user_id = $user->id;

        $contact_list = array();
        $contact_list['type'] = 'phone,all';
        $contact_data = array();
        foreach (Contact::find()->where(['user_id' => $user_id])->each() as $contact) {
            if ($contact->phone_primary_number == 1) {
                $contactInfo = new ContactInfo();
                $contactInfo->contact_id = $contact->contact_id;
                $contactInfo->user_id = $user_id;
                $contactInfo->contact_user_id = $contact->contact_user_id;
                $contactInfo->contact_first = $contact->contact_first;
                $contactInfo->contact_last = $contact->contact_last;
                $contactInfo->nickname = $contact->nickname;
                $contactInfo->relation = $contact->relation;
                $contactInfo->contact_mobile = $contact->contact_mobile;
                $contactInfo->contact_email = $contact->contact_email;
                $contactInfo->home_phone = $contact->home_phone;
                $contactInfo->work_phone = $contact->work_phone;

                $contact_user = User::findOne(['id' => $contact->contact_user_id]);
                if ($contact_user) {
                    $profileImage = new \humhub\libs\ProfileImage($contact_user->guid);
                    $pos = strpos($profileImage->getUrl(), "?m=");
                    $image = substr($profileImage->getUrl(), 0, $pos);
                    $contactInfo->photo = $image;
                }

                array_push($contact_data, $contactInfo);
            }
        }
        $contact_list['data'] = $contact_data;

        $gcm = new GCM();
        $device = Device::findOne(['device_id' => $device_id]);

        $gcm_id = $device->gcmId;
        Yii::getLogger()->log(print_r($contact_list,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm->send($gcm_id, $contact_list);

    }


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
    public function actionDevice ()
    {
        $data = Yii::$app->request->post();
        $device_data = $data['DeviceInfo'];
        $device_id = $device_data['device_id'];
        $token = $device_data['token'];
        $tel_number = $device_data['tel_number'];
        $device = Device::findOne(['device_id' => $device_id]);
        $device->device_id = $device_id;
        $device->gcmId = $token;
        $device->phone = $tel_number;
        $device->save();
        if ($this->checkDevice($device_id)) {
            $this->activation($device_id);
        }
    }
    public function checkDevice ($device_id) {
        $user = User::findOne(['device_id' => $device_id]);
        $device = Device::findOne(['device_id' => $device_id]);
        $gcmId = $device->gcmId;
        if ($user != null and $gcmId != null) {
            return true;
        }
        else {
            return false;
        }
    }
    

    public function actionSetting()
    {
        $user = Yii::$app->user->getIdentity();
        $model = new SecuritySetting();

        $model->contact_notify_setting = $user->getSetting("contact_notify_setting", 'contact', \humhub\models\Setting::Get('contact_notify_setting', 'send'));

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setSetting("contact_notify_setting", $model->contact_notify_setting, 'contact');

            Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_ContactController', 'Saved'));
        }

        return $this->render('setting', array(
            'model' => $model
        ));
    }



    public function actionImportgoogle ()
    {

        $accesstoken = '';
        $client_id = '584594431619-c8gb5m52css0vs8biotp7jcie27h0iff.apps.googleusercontent.com';
        $client_secret = 'gCqIX4YrqNH-8mYO91O_WBOJ';
        $redirect_uri = 'http://www.carecarma.tk/carecarma/index.php?r=user/contact/importgoogle';
        $simple_api_key = 'AIzaSyCdNyA6NGy8ie9ZcsSEh3adbdTXxn3LKUY';
        $max_results = 500;
        $auth_code = $_GET["code"];

        $fields = array(
            'code' => urlencode($auth_code),
            'client_id' => urlencode($client_id),
            'client_secret' => urlencode($client_secret),
            'redirect_uri' => urlencode($redirect_uri),
            'grant_type' => urlencode('authorization_code')
        );
        $post = '';
        foreach ($fields as $key => $value) {
            $post .= $key . '=' . $value . '&';
        }
        $post = rtrim($post, '&');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($curl, CURLOPT_POST, 5);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($result);
        if (isset($response->access_token)) {
            $accesstoken = $response->access_token;
            $_SESSION['access_token'] = $response->access_token;
        }

        if (isset($_GET['code'])) {

            $accesstoken = $_SESSION['access_token'];
        }

        if (isset($_REQUEST['logout'])) {
            unset($_SESSION['access_token']);
        }

        $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=' . $max_results . '&oauth_token=' . $accesstoken;
        $xmlresponse = $this->curl_file_get_contents($url);

        if ((strlen(stristr($xmlresponse, 'Authorization required')) > 0) && (strlen(stristr($xmlresponse, 'Error ')) > 0)) {
            echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
            exit();
        }

        //echo " <a href ='http://127.0.0.1/gmail_contact/callback.php?downloadcsv=1&code=4/eK2ugUwI_qiV1kE3fDa_92geg7s1DusDsN9BHzGrrTE# '><img src='images/excelimg.jpg' alt=''id ='downcsv'/></a>";
        // echo "<h3>Email Addresses:</h3>";
        $xml = new \SimpleXMLElement($xmlresponse);
        $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');

        $result = $xml->xpath('//gd:email');

        $id = Yii::$app->user->id;


        $output_array = array();
        foreach ($xml->entry as $entry) {
            foreach ($entry->xpath('gd:email') as $email) {
                $email_address = (string)$email->attributes()->address;
                $user_cur = User::findOne(['email' => $email_address]);
                $email_exist = "0";
                $email_invite = "0";
                if ($user_cur != null) {
                    $email_exist = "1";
                }
                $userInvite = Invite::findOne(['email' => $email_address]);
                if ($userInvite != null && $userInvite->user_originator_id == $id) {
                    $email_invite = "1";
                }

                $output_array[] = array(
                    (string)$entry->title,
                    (string)$entry->attributes()->href,
                    (string)$email->attributes()->address,
                    (string)$email_exist,
                    (string)$email_invite);
                Yii::getLogger()->log(print_r($output_array,true),yii\log\Logger::LEVEL_INFO,'MyLog');

            }
        }

        foreach ($result as $title) {
            $arr[] = $title->attributes()->address;
//          print_r($title->attributes()->title);
            echo $title->attributes()->displayName;
        }
//      print_r($arr);
        foreach ($arr as $key) {
//          echo $key."<br>";
        }

        $response_array = json_decode(json_encode($arr), true);
        $email_list = '';
        foreach ($response_array as $value2) {

            $email_list = ($value2[0] . ",") . $email_list;
        }
        $id = Yii::$app->user->id;
//        print_r($id);
        $searchModel = new ContactSearch();
        $searchModel->status = 'importgoogle';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        $user = User::findOne(['guid' => Yii::$app->user->guid]);

        return $this->render('importgoogle', array(
            'dataProvider' => $dataProvider,
            'data' => $output_array,
            'thisUser' => $user
        ));
    }

    function curl_file_get_contents($url) {
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

        curl_setopt($curl, CURLOPT_URL, $url);   //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);   //The number of seconds to wait while trying to connect.

        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);  //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);   //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }

    public function actionTest()
    {
        $data = 1;
        $user = Yii::$app->user->getIdentity();
        $user_id = Yii::$app->user->id;
        print_r($user);
        Yii::getLogger()->log(print_r($user_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        return $this->render('test', array(
//            'model' => $data
//        ));
    }


    public function actionImportlocal () {

        $user = User::findOne(['id' => Yii::$app->user->id]);
        $contact_list = Localcontact::findAll(['user_id' => $user->id]);
        $delete = Yii::$app->request->get('delete');

        $output_array = array();
        foreach ($contact_list as $contact) {
            $output_array[] = array(
                (string)$contact['name'],
                (string)$contact['email'],
                (string)$contact['phone_number1'],
                (string)$contact['phone_number2'],
                (string)$contact['phone_number3']);
        }

        $id = $user->id;
        $searchModel = new ContactSearch();
        $searchModel->status = 'importlocal';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        if ($delete){
            Localcontact::deleteAll(['user_id' => $user->id]);

            return $this->redirect($user->createUrl('add'));
        }

        return $this->render('importlocal', array(
            'dataProvider' => $dataProvider,
            'data' => $output_array,
            'thisUser' => $user
        ));

    }

}
