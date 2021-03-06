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
use humhub\modules\user\notifications\LinkAccepted;
use humhub\modules\user\notifications\LinkDenied;
use humhub\modules\user\notifications\Linked;
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

    //detail view of contact person
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

    //Invite contact person to user's circle
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
    

    //contact edit view
    public function actionEdit()
    {
        $user = User::findOne(['guid' => Yii::$app->user->guid]);
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('id'), 'user_id' => $user->id]);

        if ($contact == null)
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'PEOPLE not found!'));

//        $contact->scenario = 'editContact';
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
                    'readonly' => 'true',
                ),
                'phone_primary_number' => array(
                    'type' => 'checkbox',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'watch_primary_number' => array(
                    'type' => 'checkbox',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'carecarma_watch_number' => array(
                    'type' => 'checkbox',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'glass_primary_number' => array(
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
//            Yii::getLogger()->log($form->models['Contact']['glass_primary_number'], Logger::LEVEL_INFO, 'MyLog');
            if ($form->save()) {


                return $this->redirect(Url::toRoute('/user/contact'));
            }
        }
        if ($form->submitted('delete')) {
            return $this->redirect($user->createUrl('/user/contact/delete',[ 'id' => $contact->contact_id]));
        }
        return $this->render('edit', array('hForm' => $form, 'contact' => $contact, 'user' => $user));
    }

    //add contact in CareCarma sys
    public function actionAdd()
    {
        $thisUser = User::findOne(['guid' => Yii::$app->user->guid]);
        $contactUser = User::findOne(['id' => Yii::$app->request->get('connect_id')]);

        $doit = (int) Yii::$app->request->get('doit');

//        $users = User::findAll(['status'=> 1]);
        $hideGoogle = false;
        if ($this->getBrowserType() == 'iphone')
            $hideGoogle = true;



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

        //sort the results
        $thisUserMember = Membership::findAll(['user_id' => $thisUser->id]);
        $contacts = array();
        $spaces = array();
        foreach ($users as $user){
            if ($user->id != $thisUser->id ){
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


        if ($doit == 2){
            if ($contactUser == null){
                throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'People not found!'));
            }
            $needNotify = true;
            $privacy = $contactUser->getSetting("contact_notify_setting", 'contact', \humhub\models\Setting::Get('contact_notify_setting', 'send'));
            if ($privacy == User::CONTACT_NOTIFY_NOONE) {
                $needNotify = false;
            } elseif ($privacy == User::CONTACT_NOTIFY_NOCIRCLE){
                //contact user's memberships
                $membershipSpaces = Membership::findAll(['user_id' => $contactUser->id, 'status' => Membership::STATUS_MEMBER]);
                if ($membershipSpaces != null){

                    foreach ($membershipSpaces as $membershipSpace){
                        //check if thisUser is in the space
                        $userSpace = Space::findOne(['id' => $membershipSpace->space_id]);
                        if($userSpace->isMember($thisUser->id)){
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

                $thisUser->askAddContact($contactUser);
            } else {
                $thisUser->addContact($contactUser);
                $userContact = Contact::findOne(['user_id' => $thisUser->id, 'contact_user_id' => $contactUser->id]);


                $notification = new AddContact();
                $notification->source = $userContact;
                $notification->originator = $thisUser;
                $notification->send($contactUser);

            }

//            Yii::getLogger()->log([$privacy, User::CONTACT_NOTIFY_EVERYONE], Logger::LEVEL_INFO, 'MyLog');

//            return $this->redirect($thisUser->createUrl('add'));
            return $this->redirect(Url::previous());
        }

        return $this->render('add', array(
            'keyword' => $keyword,
            'users' => $contacts,
            'details' => $spaces,
            'pagination' => $pagination,
            'thisUser' => $thisUser,
            'empty' => $empty,
            'hideGoogle' => $hideGoogle,
        ));
    }

//    public function actionConsole()
//    {
//        $id = Yii::$app->user->id;
//        $searchModel = new ContactSearch();
//        $searchModel->status = 'console';
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
//
//
//
//        return $this->render('console', array(
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//            'user' => User::findOne(['id' => $id]),
//        ));
//    }


    public function actionLinkCancel()
    {
//        Yii::getLogger()->log(Yii::$app->request->get('id'), Logger::LEVEL_INFO, 'MyLog');
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $contact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => Yii::$app->request->get('id')]);


        if ($contact != null) {
            if ($contact->linked == 1)
            {
                $device_list = Device::findAll(['user_id' => $user->id]);
                $data = array();
                $data['type'] = 'contact,updated';
                if ($device_list != null) {
                    foreach($device_list as $device) {
                        $gcm = new GCM();
                        $gcm_id = $device->gcmId;
                        $gcm->send($gcm_id, $data);
                    }
                }
            }
            $contact->delete();


//            $device_id = $user->device_id;
//            $device = Device::findOne(['device_id' => $device_id]);



            $contactUser = User::findOne(['id' => $contact->contact_user_id]);
            //Delete link notification for this user
            $notificationLink = new Linked();
            $notificationLink->source = $contact;
            $notificationLink->delete($contactUser);

            $contact2 = Contact::findOne(['user_id' => $contact->contact_user_id, 'contact_user_id' => $contact->user_id]);
            if ($contact2 != null) {
                if ($contact2->linked == 1) {
                    $contactUser = User::findOne(['id' => $contact2->user_id]);
                    $device_list = Device::findAll(['user_id' => $contactUser->id]);
                    $data = array();
                    $data['type'] = 'contact,updated';
                    if ($device_list != null) {
                        foreach($device_list as $device) {
                            $gcm = new GCM();
                            $gcm_id = $device->gcmId;
                            $gcm->send($gcm_id, $data);
                        }
                    }
                }

                $contact2->delete();




            }
        }




        return $this->redirect(Url::previous());
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
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'People not found!'));
        }
        if ($doit == 2) {
            if ($contact->contact_user_id != null){
                //delete the opposite contact
                $oppContact = Contact::findOne(['user_id' => $contact->contact_user_id, 'contact_user_id' => $user->id]);
                if ($oppContact != null){
                    $oppContact->delete();


                    $contactUser = User::findOne(['id' => $oppContact->user_id]);
                    $device_list = Device::findAll(['user_id' => $contactUser->id]);
                    $data = array();
                    $data['type'] = 'contact,updated';
                    if ($device_list != null) {
                        foreach($device_list as $device) {
                            $gcm = new GCM();
                            $gcm_id = $device->gcmId;
                            $gcm->send($gcm_id, $data);
                        }
                    }



                    $data2 = array();
                    $data2['type'] = 'contact,delete';
                    $data2['contact_id'] = $oppContact->contact_id;
                    $data2['contact_user_id'] = $user->id;
                    if ($device_list != null) {
                        foreach($device_list as $device) {
                            $gcm = new GCM();
                            $gcm_id = $device->gcmId;
                            $gcm->send($gcm_id, $data2);
                        }
                    }
                }
            }


           // $user = User::findOne(['id' => $contact->user_id]);
//            $contact->notifyDevice('delete');

//            $gcm = new GCM();
//            $device_id = $user->device_id;
//            $device = Device::findOne(['device_id' => $device_id]);
//            $data = array();
//            $data['type'] = 'contact,updated';
//            if ($device != null) {
//                $gcm_id = $device->gcmId;
//                $gcm->send($gcm_id, $data);
//            }

            $user = User::findOne(['id' => $contact->user_id]);
            $device_list = Device::findAll(['user_id' => $user->id]);
            $data = array();
            $data['type'] = 'contact,updated';
            if ($device_list != null) {
                foreach($device_list as $device) {
                    $gcm = new GCM();
                    $gcm_id = $device->gcmId;
                    $gcm->send($gcm_id, $data);
                }
            }


//            $gcm2 = new GCM();
//            $data2 = array();
//            $data2['type'] = 'contact,delete';
//            $data2['contact_id'] = $contact->contact_id;
//            $data2['contact_user_id'] = $contact->contact_user_id;
//            if ($device != null) {
//                $gcm_id = $device->gcmId;
//                $gcm2->send($gcm_id, $data2);
//            }
            $data2 = array();
            $data2['type'] = 'contact,delete';
            $data2['contact_id'] = $contact->contact_id;
            $data2['contact_user_id'] = $contact->contact_user_id;
            if ($device_list != null) {
                foreach($device_list as $device) {
                    $gcm = new GCM();
                    $gcm_id = $device->gcmId;
                    $gcm->send($gcm_id, $data2);
                }
            }


            $contact->delete();

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


    public function actionLinkAccept ()
    {
        $contactUser = User::findOne(['id' => Yii::$app->user->id]);

        $user = User::findOne(['guid' => Yii::$app->request->get('uguid')]);

        $contactUser->addContact($user);
        $contact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);

        //Send notification to Accept
        $notification = new LinkAccepted();
        $notification->source = $contact;
        $notification->originator = $contactUser;
        $notification->send($user);

        //Delete link notification for this user
        $notificationLink = new Linked();
        $notificationLink->source = $contact;
        $notificationLink->delete($contactUser);

//
//        if ($contact != null) {
//            $contact->LinkUser($contactUser, $user);
//
//            $gcm = new GCM();
//            $device_id = $user->device_id;
//            $device = Device::findOne(['device_id' => $device_id]);
//            $data = array();
//            $data['type'] = 'contact,updated';
//            if ($device != null) {
//                $gcm_id = $device->gcmId;
//                $gcm->send($gcm_id, $data);
//            }
//
//            $gcm = new GCM();
//            $device_id = $contactUser->device_id;
//            $device = Device::findOne(['device_id' => $device_id]);
//            $data = array();
//            $data['type'] = 'contact,updated';
//            if ($device != null) {
//                $gcm_id = $device->gcmId;
//                $gcm->send($gcm_id, $data);
//            }
//        }


        return $this->redirect(Url::to(['index']));
    }

    public function actionLinkDecline ()
    {
        $contactUser = User::findOne(['id' => Yii::$app->user->id]);

        $user = User::findOne(['guid' => Yii::$app->request->get('uguid')]);
        $contact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);

        if ($contact != null) {
            $contact->delete();

            $oppContact = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $user->id]);
            if ($oppContact != null)
                $oppContact->delete();

            //Send notification to Deny
            $notification = new LinkDenied();
            $notification->source = $contact;
            $notification->originator = $contactUser;
            $notification->send($user);

            //Delete link notification for this user
            $notificationLink = new Linked();
            $notificationLink->source = $contact;
            $notificationLink->delete($contactUser);
        }

        return $this->redirect(Url::home());
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
            $contactInfo->relation = $this->getRelationship($contact->relation);
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

//        Yii::getLogger()->log(print_r($contact_list, true),yii\log\Logger::LEVEL_INFO,'MyLog');


        $gcm = new GCM();
        $device = Device::findOne(['device_id' => $device_id]);

        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $contact_list);
        }

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
            if ($contact->watch_primary_number == 1) {
                $contactInfo = new ContactInfo();
                $contactInfo->contact_id = $contact->contact_id;
                $contactInfo->user_id = $user_id;
                $contactInfo->contact_user_id = $contact->contact_user_id;
                $contactInfo->contact_first = $contact->contact_first;
                $contactInfo->contact_last = $contact->contact_last;
                $contactInfo->nickname = $contact->nickname;
                $contactInfo->relation = $$this->getRelationship($contact->relation);
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

        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $contact_list);
        }
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
                $contactInfo->relation = $this->getRelationship($contact->relation);
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

        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $contact_list);
        }

    }


    public function actionCwatchallcontact ()
    {

        $data = Yii::$app->request->post();
        $device_id = $data['device_id'];
        $user = User::findOne(['username' => $data['username']]);
        $user_id = $user->id;

        $contact_list = array();
        $contact_list['type'] = 'Cwatch,all';
        $contact_data = array();

        foreach (Contact::find()->where(['user_id' => $user_id])->each() as $contact) {
            if ($contact->carecarma_watch_number == 1) {
                $contactInfo = new ContactInfo();
                $contactInfo->contact_id = $contact->contact_id;
                $contactInfo->user_id = $user_id;
                $contactInfo->contact_user_id = $contact->contact_user_id;
                $contactInfo->contact_first = $contact->contact_first;
                $contactInfo->contact_last = $contact->contact_last;
                $contactInfo->nickname = $contact->nickname;
                $contactInfo->relation = $this->getRelationship($contact->relation);
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

        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $contact_list);
        }
    }

    public function actionGlassallcontact ()
    {

        $data = Yii::$app->request->post();
        $device_id = $data['device_id'];
        $user = User::findOne(['username' => $data['username']]);
        $user_id = $user->id;

        $contact_list = array();
        $contact_list['type'] = 'Glass,all';
        $contact_data = array();

        foreach (Contact::find()->where(['user_id' => $user_id])->each() as $contact) {
            if ($contact->glass_primary_number == 1) {
                $contactInfo = new ContactInfo();
                $contactInfo->contact_id = $contact->contact_id;
                $contactInfo->user_id = $user_id;
                $contactInfo->contact_user_id = $contact->contact_user_id;
                $contactInfo->contact_first = $contact->contact_first;
                $contactInfo->contact_last = $contact->contact_last;
                $contactInfo->nickname = $contact->nickname;
                $contactInfo->relation = $this->getRelationship($contact->relation);
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

        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $contact_list);
        }
    }


    public function actionImportgoogle ()
    {

        $accesstoken = '';
//        $client_id = '584594431619-c8gb5m52css0vs8biotp7jcie27h0iff.apps.googleusercontent.com';
//        $client_secret = 'gCqIX4YrqNH-8mYO91O_WBOJ';
        $redirect_uri = 'http://www.carecarma.com/carecarma/index.php?r=user/contact/importgoogle';
//        $simple_api_key = 'AIzaSyCdNyA6NGy8ie9ZcsSEh3adbdTXxn3LKUY';
        $client_id = '455820633290-p8i2kjqqtq1h9ve2p1qe63u3ed3ojlb5.apps.googleusercontent.com';
        $client_secret = 'jcAZIGceSGIMQIdo_pEaKglX';
//        $redirect_uri = Yii::$app->request->hostInfo.Url::toRoute('contact/importgoogle');
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

    private function getBrowserType() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {
            $browser = 'Maxthon';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {
            $browser = 'IE12.0';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {
            $browser = 'IE11.0';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
            $browser = 'IE10.0';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
            $browser = 'IE9.0';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
            $browser = 'IE8.0';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
            $browser = 'IE7.0';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
            $browser = 'IE6.0';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {
            $browser = 'NetCaptor';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
            $browser = 'Netscape';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {
            $browser = 'Lynx';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
            $browser = 'Opera';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
            $browser = 'Google';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
            $browser = 'Firefox';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
            $browser = 'Safari';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPod')) {
            $browser = 'iphone';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
            $browser = 'iphone';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
            $browser = 'android';
        } else {
            $browser = 'other';
        }


        return $browser;
    }

    private function getRelationship($key)
    {
        $relationship_groups = Yii::$app->params['availableRelationship'];
        $relation = $key;
        foreach ($relationship_groups as $relationship_group){
            if (is_array($relationship_group)){
                if (isset($relationship_group[$key])){
                    $relation = $relationship_group[$key];
                    break;
                }
            }
        }
        return $relation;
    }

}
