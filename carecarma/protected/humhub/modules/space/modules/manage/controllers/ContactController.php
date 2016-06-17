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
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);

        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user->id);

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
            'space' => $space,
            'user' => $user,
        ]);
    }

    public function actionView()
    {
        $space = $this->getSpace();
        $Cid = (int) Yii::$app->request->get('Cid');


        $contact = Contact::findOne(['contact_id' => $Cid]);

        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_ContactController', 'Contact not found!'));
        }



        return $this->render('view', array('contact' => $contact, 'space' => $space));
    }

    public function actionEdit()
    {
        $space = $this->getSpace();
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('Cid')]);

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

                $contact->notifyDevice('update');

                return $this->redirect(Url::toRoute(['/space/manage/contact','id' => $id, 'sguid' => $space->guid]));
            }
        }


        if ($form->submitted('delete')) {
            return $this->redirect(Url::toRoute(['/space/manage/contact/delete', 'Cid' => $contact->contact_id,'id' => $contact->user_id, 'sguid' => $space->guid]));
        }

        return $this->render('edit', array(
            'hForm' => $form,
            'space' => $space,
            'contact' => $contact
            ));
    }


    public function actionAdd()
    {
        $space = $this->getSpace();
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);
        $contactModel = new Contact();

        $contactModel->user_id = $user->id;


        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();



        // Add User Form
        $definition['elements']['Contact'] = array(
            'type' => 'form',
            'title' => Yii::t('SpaceModule.controllers_ContactController', 'New Contact'),
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
            ),
        );



        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'label' => Yii::t('SpaceModule.controllers_ContactController', 'Add'),
            ),
        );

        $form = new HForm($definition);
        $form->models['Contact'] = $contactModel;


        if ($form->submitted('save') && $form->validate()) {


            if ($form->models['Contact']->save()) {


                $contactModel->notifyDevice('add');

                return $this->redirect(Url::to(['/space/manage/contact','id' => $user->id, 'sguid' => $space->guid]));
            }
        }

        return $this->render('add', array(
            'hForm' => $form,
            'space' => $space,
            'user' => $user,
        ));
    }

    /**
     * Deletes a user permanently
     */
    public function actionDelete()
    {
        $space = $this->getSpace();
        $id = (int) Yii::$app->request->get('id');
        $Cid = (int) Yii::$app->request->get('Cid');
        $doit = (int) Yii::$app->request->get('doit');


        $contact = Contact::findOne(['contact_id' => $Cid]);

        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_ContactController', 'Contact not found!'));
        }

        if ($doit == 2) {

            $contact->delete();

            $contact->notifyDevice('delete');

            return $this->redirect(Url::to(['/space/manage/contact/index','id' => $id, 'sguid' => $space->guid]));
        }

        return $this->render('delete', array('model' => $contact, 'space' => $space));
    }

    public function actionImport()
    {
        $thisSpace = $this->getSpace();
        $id = (int) Yii::$app->request->get('id');

        $userSpaces = Membership::findAll(['user_id' => $id]);
        $contacts = array();
        $spaces = array();
        foreach ($userSpaces as $space){
            if ($space !== null)
            {
                $spaceId = $space->space_id;
                foreach (Membership::find()->where(['space_id' => $spaceId])->each() as $spaceContact){
                    $userId = $spaceContact->user_id;
                    $existContact = Contact::findOne(['user_id' => $id, 'contact_user_id' => $userId]);
                    if ($userId != $id && !$existContact){
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
        $contactModel = new Contact();
        $contactModel->user_id = $id;

        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();
        // Add User Form
        $definition['elements']['Contact'] = array(
            'type' => 'form',
            'elements' => array(
                'contact_user_id' => array(
                    'class' => 'form-control',
                    'maxlength' => 11,
                    'type' => 'hidden',
                ),
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
            ),
        );

        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'label' => Yii::t('SpaceModule.controllers_ContactController', 'Save'),
                'class' => 'btn btn-primary',
            )
        );

        $form = new HForm($definition);
        $form->models['Contact'] = $contactModel;

        if ($form->submitted('save') && $form->validate()) {

            if ($form->save()) {

                $contactModel->notifyDevice('add');

                return $this->redirect(Url::toRoute(['/space/manage/contact/import','id' => $id, 'sguid' => $thisSpace->guid]));
            }
        }
        return $this->render('import', array(
            'space' => $thisSpace,
            'keyword' => $keyword,
            'hForm' => $form,
            'model' => $contactModel,
            'users' => $searchResultSet->getResultInstances(),
            'details' => $spaces,
            'pagination' => $pagination
        ));
    }

    public function actionConnect()
    {
        $thisSpace = $this->getSpace();
        $id = (int) Yii::$app->request->get('id');

        $doit = (int) Yii::$app->request->get('doit');
        $Cid = (int) Yii::$app->request->get('Cid');
        $contact = Contact::findOne(['contact_id' => $Cid]);

        $limitUsers = array();
        $spaces = array();
        foreach (Profile::findAll(['mobile' => $contact->contact_mobile]) as $userProfile) {
            $userId =  $userProfile->user_id;
            $limitUsers[] = User::findOne(['id' => $userId]);
            $spaces[$userId] = 0;
        }
        $userSpaces = Membership::findAll(['user_id' => $id]);

        foreach ($userSpaces as $space){
            if ($space !== null)
            {
                $spaceId = $space->space_id;
                foreach (Membership::findAll(['space_id' => $spaceId, 'status' => 3]) as $spaceContact){
                    $userId = $spaceContact->user_id;
                    $existContact = Contact::findOne(['user_id' => $id, 'contact_user_id' => $userId]);
                    if ($userId != $id && !$existContact){
                        $limitUsers[] = User::findOne(['id' => $userId]);
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
            'limitUsers' => $limitUsers,
        ];


        $searchResultSet = Yii::$app->search->find($keyword, $searchOptions);
        $pagination = new \yii\data\Pagination(['totalCount' => $searchResultSet->total, 'pageSize' => $searchResultSet->pageSize]);


        $connect_user_id = (int) Yii::$app->request->get('connect_id');
        if ($doit == 2) {
            $contact_user = User::findOne(['id' => $connect_user_id]);
            $contact->contact_user_id = $connect_user_id;
            $contact->contact_first = $contact_user->profile->firstname;
            $contact->contact_last = $contact_user->profile->lastname;
            $contact->contact_mobile = $contact_user->profile->mobile;
            $contact->home_phone = $contact_user->profile->phone_private;
            $contact->work_phone = $contact_user->profile->phone_work;
            $contact->contact_email = $contact_user->email;
            if ($contact_user->device_id != null)
            {
                $contact->device_phone = $contact_user->device->phone;
            }
            $contact->save();

            $contact->notifyDevice('update');

            return $this->redirect(Url::to(['/space/manage/contact', 'id' => $id, 'sguid' => $thisSpace->guid]));
        }

        return $this->render('connect', array(
            'space' => $thisSpace,
            'keyword' => $keyword,
            'users' => $searchResultSet->getResultInstances(),
            'pagination' => $pagination,
            'contact' => $contact,
            'details' => $spaces,
            'connnect_id' => $connect_user_id
        ));
    }

    public function actionDisconnect ()
    {
        $thisSpace = $this->getSpace();
        $id = (int) Yii::$app->request->get('id');

        $Cid = (int) Yii::$app->request->get('Cid');
        $contact = Contact::findOne(['contact_id' => $Cid]);
        if ($contact != null) {
            $contact->contact_user_id = null;
            $contact->save();
            $contact->notifyDevice('disconnect');
        }


        return $this->redirect(Url::toRoute(['/space/manage/contact/edit', 'Cid' => $Cid, 'id' => $id, 'sguid' => $thisSpace->guid]));
    }

}
