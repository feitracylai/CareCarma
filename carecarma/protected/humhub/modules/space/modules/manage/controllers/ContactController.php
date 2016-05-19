<?php

namespace humhub\modules\space\modules\manage\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
use humhub\libs\GCM;
use humhub\libs\Push;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Password;
use humhub\modules\user\models\Group;
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



        return $this->render('view', array('model' => $contact, 'space' => $space));
    }

    public function actionEdit()
    {
        $space = $this->getSpace();
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('Cid')]);

        if ($contact == null)
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_ContactController', 'Contact not found!'));


        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();
        // Add User Form
        $definition['elements']['Contact'] = array(
            'type' => 'form',
            'title' => 'Contact',
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
                'contact_mobile' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'contact_email' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'nickname' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
            ),
        );

//        // Add Profile Form
//        $definition['elements']['Profile'] = array_merge(array('type' => 'form'), $profile->getFormDefinition());

        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'label' => Yii::t('SpaceModule.controllers_ContactController', 'Save'),
                'class' => 'btn btn-primary',
            ),

            'delete' => array(
                'type' => 'submit',
                'label' => Yii::t('SpaceModule.controllers_ContactController', 'Delete'),
                'class' => 'btn btn-danger',
            ),
        );

        $form = new HForm($definition);
        $form->models['Contact'] = $contact;
        $user = User::findOne(['id' => $contact->user_id]);
        if ($form->submitted('save') && $form->validate()) {
            if ($form->save()) {

                $form->models['Contact']->isRead = 'false';


                if ($user->gcmId != null){
                    $gcm = new GCM();
                    $push = new Push();

                    $push->setTitle('contact');
                    $push->setData('update');
                    $push->setAID($contact->AndroidId);


                    $gcm_registration_id = $user->gcmId;

                $gcm->send($gcm_registration_id, $push->getPush());


                }


                return $this->redirect(Url::toRoute(['/space/manage/contact','id' => $user->id, 'sguid' => $space->guid]));
            }
        }


        if ($form->submitted('delete')) {
            return $this->redirect(Url::toRoute(['/space/manage/contact/delete', 'Cid' => $contact->contact_id,'id' => $user->id, 'sguid' => $space->guid]));
        }

        return $this->render('edit', array(
            'hForm' => $form,
            'space' => $space
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
                'contact_mobile' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'contact_email' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'nickname' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
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

                if ($user->gcmId != null){
                    $gcm = new GCM();
                    $push = new Push();

                    $push->setTitle('contact');
                    $push->setData('add');



                    $gcm_registration_id = $user->gcmId;

                    $gcm->send($gcm_registration_id, $push->getPush());


                }



                return $this->redirect(Url::to(['/space/manage/contact/index','id' => $user->id, 'sguid' => $space->guid]));
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
        $Cid = (int) Yii::$app->request->get('Cid');
        $doit = (int) Yii::$app->request->get('doit');


        $contact = Contact::findOne(['contact_id' => $Cid]);

        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_ContactController', 'Contact not found!'));
        }

        if ($doit == 2) {

            $contact->delete();

            $user = User::findOne(['id' => $contact->user_id]);

            if ($user->gcmId != null){
                $gcm = new GCM();
                $push = new Push();

                $push->setTitle('contact');
                $push->setData('delete');
                $push->setAID($contact->AndroidId);


                $gcm_registration_id = $user->gcmId;

                $gcm->send($gcm_registration_id, $push->getPush());


            }

            return $this->redirect(Url::to(['/space/manage/contact/index','id' => $user->id, 'sguid' => $space->guid]));
        }

        return $this->render('delete', array('model' => $contact, 'space' => $space));
    }

    public function actionImport()
    {
        $userSpaces = Membership::findAll(['user_id' => Yii::$app->user->id]);
        $spaces = array();
        foreach ($userSpaces as $space){
            $spaces[] = Space::findOne(['id' => $space->space_id]);
        }


        $keyword = Yii::$app->request->get('keyword', "");
        $page = (int) Yii::$app->request->get('page', 1);


        $searchOptions = [
            'model' => \humhub\modules\user\models\User::className(),
            'page' => $page,
//            'pageSize' => $this->module->pageSize,
        ];




        $searchResultSet = Yii::$app->search->find($keyword, $searchOptions);

        $pagination = new \yii\data\Pagination(['totalCount' => $searchResultSet->total, 'pageSize' => $searchResultSet->pageSize]);



        return $this->render('import', array(
            'keyword' => $keyword,
//            'group' => $group,
            'users' => $searchResultSet->getResultInstances(),
            'pagination' => $pagination
        ));
    }

}
