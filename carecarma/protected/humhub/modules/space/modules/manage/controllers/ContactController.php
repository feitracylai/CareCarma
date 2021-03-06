<?php

namespace humhub\modules\space\modules\manage\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\log\Logger;
use yii\web\HttpException;
use humhub\libs\GCM;
use humhub\libs\Push;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Password;
use humhub\modules\user\models\Profile;
use humhub\modules\user\models\Device;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\space\modules\manage\components\Controller;
use humhub\modules\space\modules\manage\models\DeviceUserSearch;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\ContactSearch;
use humhub\compat\HForm;
use humhub\models\Setting;

/**
 * ContactController implements the CRUD actions for contact model.
 * @property mixed humhub
 */
class ContactController extends Controller
{


    /**
     * Lists all contact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $space = $this->getSpace();

        $user = User::findOne(['guid' => Yii::$app->request->get('rguid')]);

        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user->id);
//        Yii::getLogger()->log(Yii::$app->request->post(), Logger::LEVEL_INFO, 'MyLog');
        // Relationship Change
        if (Yii::$app->request->post('dropDownColumnSubmit') || Yii::$app->request->post('checkSubmit')) {
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
            'space' => $space,
            'user' => $user,
        ]);
    }



    public function actionEdit()
    {
        $space = $this->getSpace();
        $user = User::findOne(['guid' => Yii::$app->request->get('rguid')]);
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('Cid'), 'user_id' => $user->id]);
//        $contact->scenario = 'editContact';

        if ($contact == null)
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_ContactController', 'Contact not found!'));


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
        if ($space->isMember($contact->contact_user_id)){
            $definition['buttons'] = array(
                'back' => array(
                    'type' => 'submit',
                    'label' => Yii::t('UserModule.controllers_ContactController', '<< Back'),
                    'class' => 'btn  pull-left btn-primary',
                ),
                'save' => array(
                    'type' => 'submit',
                    'label' => Yii::t('UserModule.controllers_ContactController', 'Save'),
                    'class' => 'btn btn-primary pull-right',
                ),

            );
        } else {
            $definition['buttons'] = array(
                'back' => array(
                    'type' => 'submit',
                    'label' => Yii::t('UserModule.controllers_ContactController', '<< Back'),
                    'class' => 'btn  pull-left btn-primary',
                ),

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
        }


        $form = new HForm($definition);
        $form->models['Contact'] = $contact;


        if ($form->submitted('save') && $form->validate()) {

            if ($form->save()) {

                return $this->redirect($space->createUrl('index', ['rguid' => $user->guid]));
            }
        }

        if ($form->submitted('back')) {
            return $this->redirect($space->createUrl('index', ['rguid' => $user->guid]));
        }


        if ($form->submitted('delete')) {
            return $this->redirect($space->createUrl('delete', ['Cid' => $contact->contact_id, 'rguid' => $user->guid]));
        }

        return $this->render('edit', array(
            'hForm' => $form,
            'space' => $space,
            'contact' => $contact,
            'user' => $user,
            ));
    }


//    public function actionConsole()
//    {
//        $space = $this->getSpace();
//
//        $user = User::findOne(['guid' => Yii::$app->request->get('rguid')]);
//
//        $searchModel = new ContactSearch();
//        $searchModel->status = 'console';
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user->id);
//
//        return $this->render('console', array(
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//            'user' => $user,
//            'space' => $space,
//        ));
//    }

//    public function actionLinkAccept ()
//    {
//        $contactUser = User::findOne(['guid' => Yii::$app->request->get('rguid')]);
//
//        $user = User::findOne(['guid' => Yii::$app->request->get('uguid')]);
//        $contact = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);
//
//        if ($contact != null) {
//            $contact->LinkUser($contactUser, $user);
//        }
//
//
//        return $this->redirect(Url::to(['console']));
//    }
//
//    public function actionLinkCancel()
//    {
//        $user = User::findOne(['guid' => Yii::$app->request->get('rguid')]);
//        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('id')]);
//        if ($contact != null) {
//            $contact->CancelLink($user);
//        }
//
//        return $this->redirect(Url::to(['console']));
//    }


    /**
     * Deletes a user permanently
     */
    public function actionDelete()
    {
        $space = $this->getSpace();
        $user = User::findOne(['guid' => Yii::$app->request->get('rguid')]);
        $Cid = (int) Yii::$app->request->get('Cid');
        $doit = (int) Yii::$app->request->get('doit');


        $contact = Contact::findOne(['contact_id' => $Cid, 'user_id' => $user->id]);

        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_ContactController', 'Contact not found!'));
        }

        if ($doit == 2) {


//            $contact->notifyDevice('delete');
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

            $gcm = new GCM();
            $device_id = $user->device_id;
            $device = Device::findOne(['device_id' => $device_id]);
            $data = array();
            $data['type'] = 'contact,updated';
            if ($device != null) {
                $gcm_id = $device->gcmId;
                $gcm->send($gcm_id, $data);
            }
            $gcm2 = new GCM();
            $data2 = array();
            $data2['type'] = 'contact,delete';
            $data2['contact_id'] = $contact->contact_id;
            $data2['contact_user_id'] = $contact->contact_user_id;
            if ($device != null) {
                $gcm_id = $device->gcmId;
                $gcm2->send($gcm_id, $data2);
            }

            $contact->delete();

            return $this->redirect($space->createUrl('index', ['rguid' => $user->guid]));
        }

        return $this->render('delete', array(
            'model' => $contact,
            'space' => $space,
            'user' => $user,
            ));
    }

//    public function actionAdd()
//    {
//        $thisSpace = $this->getSpace();
//        $Ruser = User::findOne(['guid' => Yii::$app->request->get('rguid')]);
//        $doit = (int) Yii::$app->request->get('doit');
//
//
//        $empty = false;
//        $keyword = Yii::$app->request->get('keyword', "");
//        if ($keyword == "")
//            $empty = true;
//
//        $page = (int) Yii::$app->request->get('page', 1);
//
//        $searchOptions = [
//            'model' => \humhub\modules\user\models\User::className(),
//            'page' => $page,
//            'pageSize' => Setting::Get('paginationSize'),
//        ];
//
//        $searchResultSet = Yii::$app->search->find($keyword, $searchOptions);
//
//        $users = $searchResultSet->getResultInstances();
//        $RMember = Membership::findAll(['user_id' => $Ruser->id]);
//        $contacts = array();
//        $spaces = array();
//        foreach ($users as $user){
//            $existContact = Contact::findAll(['user_id' => $Ruser->id, 'contact_user_id' => $user->id, 'linked' => 1]);
//            if ($user->id != $Ruser->id && !$existContact){
//                if ($RMember != null){
//                    $isSameSpace = false;
//                    foreach ($RMember as $s){
//                        $m = Membership::findOne(['space_id' => $s->space_id, 'user_id' => $user->id]);
//                        if ($m != null){
//                            $isSameSpace = true;
//                            $spaces[$user->id] = Space::findOne(['id' => $s->space_id]);
//                            break;
//                        }
//                    }
//                    if ($isSameSpace){
//                        array_unshift($contacts, $user);
//                    } else {
//                        array_push($contacts, $user);
//                    }
//
//                } else {
//                    array_push($contacts, $user);
//                }
//            }
//        }
//
//
//
//        $pagination = new \yii\data\Pagination(['totalCount' => count($contacts)]);
//
//        if ($doit == 2) {
//            $contactUser = User::findOne(['id' => Yii::$app->request->get('connect_id')]);
//            $contact = new Contact();
//
//            if ($contactUser->guid == Yii::$app->user->guid)
//            {
//                $contact->contact_user_id = $contactUser->id;
//                $contact->contact_first = $contactUser->profile->firstname;
//                $contact->contact_last = $contactUser->profile->lastname;
//                $contact->contact_mobile = $contactUser->profile->mobile;
//                $contact->home_phone = $contactUser->profile->phone_private;
//                $contact->work_phone = $contactUser->profile->phone_work;
//                $contact->contact_email = $contactUser->email;
//                $contact->linked = 1;
//                $contact->save();
//            } else {
//                $contact->sendLink($contactUser, $user);
//            }
//
//            return $this->redirect($thisSpace->createUrl('add', ['rguid' => $user->guid]));
//        }
//
//        return $this->render('add', array(
//            'space' => $thisSpace,
//            'receiver' => $Ruser,
//            'keyword' => $keyword,
//            'users' => $contacts,
//            'details' => $spaces,
//            'pagination' => $pagination,
//            'empty' => $empty,
//
//        ));
//    }






}
