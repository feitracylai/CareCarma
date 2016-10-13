<?php
namespace humhub\modules\user\controllers;

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
    public $subLayout = "@humhub/modules/user/views/account/_layout";
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
		
		// Relationship Change
        if (Yii::$app->request->post('dropDownColumnSubmit')) {
            Yii::$app->response->format = 'json';
            $contact = Contact::findOne(['contact_id' => Yii::$app->request->post('contact_id')]);
            if ($contact === null) {
                throw new \yii\web\HttpException(404, 'Could not find contacts!');
            }


            if ($contact->load(Yii::$app->request->post()) && $contact->validate() && $contact->save()) {

                return Yii::$app->request->post();
            }
            return $contact->getErrors();
        }
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user,
        ]);
    }
    public function actionView()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id, 'user_id' => $user->id]);
        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));
        }
        return $this->render('view', array(
            'contact' => $contact,
            'user' => $user
        ));
    }
    public function actionEdit()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('id'), 'user_id' => $user->id]);
        $contact->scenario = 'editContact';
        if ($contact == null)
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));
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
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $contactUser = User::findOne(['id' => Yii::$app->request->get('connect_id')]);
        $doit = (int) Yii::$app->request->get('doit');

        $userSpaces = Membership::findAll(['user_id' => $user->id, 'status' => 3]);
        $contacts = array();
        $spaces = array();
        foreach ($userSpaces as $space){
            if ($space !== null)
            {
                $spaceId = $space->space_id;
                foreach (Membership::find()->where(['space_id' => $spaceId])->each() as $spaceContact){
                    $userId = $spaceContact->user_id;
                    $existContact = Contact::findOne(['user_id' => Yii::$app->user->id, 'contact_user_id' => $userId]);
                    if ($userId != Yii::$app->user->id && !$existContact && $spaceContact->status == 3){
                        $contacts[] = User::findOne(['id' => $userId]);
                        $spaces[$userId] = $spaceId;
                    }
                }
            }
        }
        $keyword = Yii::$app->request->get('keyword', "");
        $page = (int) Yii::$app->request->get('page', 1);
        $searchOptions = [
            'model' => \humhub\modules\user\models\User::className(),
            'page' => $page,
            'limitUsers' => $contacts,
        ];
        $searchResultSet = Yii::$app->search->find($keyword, $searchOptions);
        $pagination = new \yii\data\Pagination(['totalCount' => $searchResultSet->total, 'pageSize' => $searchResultSet->pageSize]);


        if ($doit == 2){
            $needNotify = true;
            $privacy = $contactUser->getSetting("contact_notify_setting", 'contact', \humhub\models\Setting::Get('contact_notify_setting', 'send'));
            if ($privacy == User::CONTACT_NOTIFY_NOONE) {
                $needNotify = false;
            } elseif ($privacy == User::CONTACT_NOTIFY_NOCIRCLE){
                $membershipSpaces = Membership::findAll(['user_id' => $contactUser->id]);
                if ($membershipSpaces != null){
                    foreach ($membershipSpaces as $membershipSpace){
                        $userMemeber = Membership::findOne(['space_id' => $membershipSpace->space_id, 'user_id' => $user->id]);
                        if($userMemeber != null){
                            $needNotify = false;
                            break;
                        }
                    }
                }
            }

            if ($needNotify == true){
                $contact = new Contact();
                $contact->sendLink($contactUser, $user);
            } else {
                //User add contact
                $userContact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);
                if ($userContact == null){
                    $userContact = new Contact();
                    $userContact->user_id = $user->id;
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
                $userContact->notifyDevice('add');

                $notification = new AddContact();
                $notification->source = $userContact;
                $notification->originator = $user;
                $notification->send($contactUser);

                //contact user add contact
                $newContact = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $user->id]);
                if ($newContact == null){
                    $newContact = new Contact();
                    $newContact->user_id = $contactUser->id;
                    $newContact->contact_user_id = $user->id;
                }
                $newContact->contact_first = $user->profile->firstname;
                $newContact->contact_last = $user->profile->lastname;
                $newContact->contact_mobile = $user->profile->mobile;
                $newContact->contact_email = $user->email;
                $newContact->linked = 1;
                $newContact->home_phone = $user->profile->phone_private;
                $newContact->work_phone = $user->profile->phone_work;
                if ($user->device_id != null)
                {
                    $newContact->device_phone = $user->device->phone;
                }
                $newContact->save();
                $newContact->notifyDevice('add');
            }

//            Yii::getLogger()->log([$privacy, User::CONTACT_NOTIFY_EVERYONE], Logger::LEVEL_INFO, 'MyLog');

            return $this->redirect($user->createUrl('add'));
        }

        return $this->render('add', array(
            'keyword' => $keyword,
            'users' => $searchResultSet->getResultInstances(),
            'details' => $spaces,
            'pagination' => $pagination,
            'thisUser' => $user,
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


        return $this->redirect(Url::to(['console']));
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
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));
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
            return $this->redirect(Url::toRoute('index'));
        }
        return $this->render('delete', array('model' => $contact, 'user' => $user));
    }

    public function actionInvite()
    {

        $model = new \humhub\modules\user\models\forms\Invite;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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

    public function actionImport()
    {


        return $this->render('import', array(

        ));
    }


    public function actionConnect()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $doit = (int) Yii::$app->request->get('doit');
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id, 'user_id' => $user->id]);
        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));
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


        return $this->redirect(Url::to(['console']));
    }

    public function actionLinkDecline ()
    {
        $contactUser = User::findOne(['id' => Yii::$app->user->id]);

        $user = User::findOne(['guid' => Yii::$app->request->get('uguid')]);
        $contact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);

        if ($contact != null) {
            $contact->DenyLink($contactUser, $user);
        }

        return $this->redirect(Url::to(['console']));
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
        $user_id = Yii::$app->user->id;
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
//            Yii::getLogger()->log(print_r(json_encode($contact->getAttributes(array('user_id', 'contact_user_id', 'nickname'))),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        }
        $contact_list['data'] = $contact_data;

//        ContactInfo::notify($contact_list);

        $gcm = new GCM();
        $user = User::findOne(['id' => $contact_list['data'][0]->user_id]);
        $device = Device::findOne(['device_id' => $user->device_id]);

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
        $user_id = Yii::$app->user->id;
        $contact_list = array();
        $contact_list['type'] = 'watch,all';
        $contact_data = array();
        foreach (Contact::find()->where(['user_id' => $user_id])->each() as $contact) {
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
                array_push($contact_data, $contactInfo);
            }
        }
        $contact_list['data'] = $contact_data;

        $gcm = new GCM();
        $user = User::findOne(['id' => $contact_list['data'][0]->user_id]);
        $device = Device::findOne(['device_id' => $user->device_id]);

        $gcm_id = $device->gcmId;
        Yii::getLogger()->log(print_r($contact_list,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm->send($gcm_id, $contact_list);
    }

    public function actionPhoneallcontact ()
    {
        $user_id = Yii::$app->user->id;
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
                array_push($contact_data, $contactInfo);
            }
        }
        $contact_list['data'] = $contact_data;

        $gcm = new GCM();
        $user = User::findOne(['id' => $contact_list['data'][0]->user_id]);
        $device = Device::findOne(['device_id' => $user->device_id]);

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





}
