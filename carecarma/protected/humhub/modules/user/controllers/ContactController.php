<?php

namespace humhub\modules\user\controllers;

use humhub\modules\user\models\ProfileField;
use Yii;
use yii\helpers\Url;
use humhub\compat\HForm;
use humhub\modules\space\models\forms\InviteForm;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\User;
use humhub\modules\user\models\ContactSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use humhub\libs\GCM;
use humhub\libs\Push;


/**
 * ContactController implements the CRUD actions for contact model.
 * @property mixed humhub
 */
class ContactController extends Controller
{

    public $subLayout = "@humhub/modules/user/views/account/_layout";


    /**
     * Lists all contact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView()
    {

        $id = (int) Yii::$app->request->get('id');


        $contact = Contact::findOne(['contact_id' => $id]);

        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));
        }



        return $this->render('view', array('model' => $contact));
    }

    public function actionEdit()
    {
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('id')]);

        if ($contact == null)
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));

//        $contact->scenario = 'editContact';
//        $contact->profile->scenario = 'editContact';
//        $profile = $contact->profile;

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
                'label' => Yii::t('UserModule.controllers_ContactController', 'Save'),
                'class' => 'btn btn-primary',
            ),
//            'become' => array(
//                'type' => 'submit',
//                'label' => Yii::t('AdminModule.controllers_UserController', 'Become this user'),
//                'class' => 'btn btn-danger',
//            ),
            'delete' => array(
                'type' => 'submit',
                'label' => Yii::t('UserModule.controllers_ContactController', 'Delete'),
                'class' => 'btn btn-danger',
            ),
        );

        $form = new HForm($definition);
        $form->models['Contact'] = $contact;
//        $form->models['Profile'] = $profile;

        if ($form->submitted('save') && $form->validate()) {
            if ($form->save()) {

                $form->models['Contact']->isRead = 'false';
                $user = User::findOne(['id' => $contact->user_id]);

                if ($user->gcmId != null){
                    $gcm = new GCM();
                    $push = new Push();

                    $push->setTitle('contact');
                    $push->setData('update');



                    $gcm_registration_id = $user->gcmId;
//                    $gcm_registration_id = 'mOCdyAu3Z5s:APA91bH6SWfeHb2bDluKmUVvRAFEA4KkMYfvRmK0BCvod5-fwJvLpAq59Mqy47M82704PguTwd4gFKpv31446iE86mn1J86eaPfYXGZJp2ZxKjVJwzKO6lTqVXzNo001kkZnAI2uqg69';

//                    $gcm->send($gcm_registration_id, 'contact_update');
                $gcm->send($gcm_registration_id, $push->getPush());


                }


                return $this->redirect(Url::toRoute('/user/contact'));
            }
        }

        // This feature is used primary for testing, maybe remove this in future
//        if ($form->submitted('become')) {
//
//            Yii::$app->user->switchIdentity($form->models['User']);
//            return $this->redirect(Url::toRoute("/"));
//        }

        if ($form->submitted('delete')) {
            return $this->redirect(Url::toRoute(['/user/contact/delete', 'id' => $contact->contact_id]));
        }

        return $this->render('edit', array('hForm' => $form));
    }


    public function actionAdd()
    {
        $contactModel = new Contact();
//        $contactModel->scenario = 'addContact';

        $contactModel->user_id = Yii::$app->user->id;

        $keyword = Yii::$app->request->get('keyword', "");
        $model = new InviteForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->getInvites() as $InviteUser) {
                $contactModel->contact_first = $InviteUser->profile->firstname;
                $contactModel->contact_last = $InviteUser->profile->lastname;
                $contactModel->contact_mobile = $InviteUser->profile->mobile;
                $contactModel->contact_email = $InviteUser->email;
            }
        }

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

//        if ($inviteModel->load(Yii::$app->request->post()) && $inviteModel->validate()){
//            foreach ($inviteModel->getInvites() as $inviteUser) {
//                $
//            }
//        }


        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'label' => Yii::t('UserModule.controllers_ContactController', 'Add'),
            ),
        );

        $form = new HForm($definition);
        $form->models['Contact'] = $contactModel;


        if ($form->submitted('save') && $form->validate()) {

//            $this->forcePostRequest();

//            $form->models['Contact']->status = User::STATUS_ENABLED;
            if ($form->models['Contact']->save()) {

                $user = User::findOne(['id' => $contactModel->user_id]);

                if ($user->gcmId != null){
                    $gcm = new GCM();
                    $push = new Push();

                    $push->setTitle('contact');
                    $push->setData('add');



                    $gcm_registration_id = $user->gcmId;

                    $gcm->send($gcm_registration_id, $push->getPush());


                }



                return $this->redirect(Url::to(['index']));
            }
        }

        return $this->render('add', array('hForm' => $form, 'keyword' => $keyword, 'model' => $model));
    }

    /**
     * Deletes a user permanently
     */
    public function actionDelete()
    {

        $id = (int) Yii::$app->request->get('id');
        $doit = (int) Yii::$app->request->get('doit');


        $contact = Contact::findOne(['contact_id' => $id]);

        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));
        }

        if ($doit == 2) {
//
//            $this->forcePostRequest();
//
//            foreach (\humhub\modules\space\models\Membership::GetUserSpaces($contact->id) as $space) {
//                if ($space->isSpaceOwner($contact->id)) {
//                    $space->addMember(Yii::$app->user->id);
//                    $space->setSpaceOwner(Yii::$app->user->id);
//                }
//            }
            $contact->delete();

            $user = User::findOne(['id' => $contact->user_id]);

            if ($user->gcmId != null){
                $gcm = new GCM();
                $push = new Push();

                $push->setTitle('contact');
                $push->setData('delete');



                $gcm_registration_id = $user->gcmId;
//                    $gcm_registration_id = 'mOCdyAu3Z5s:APA91bH6SWfeHb2bDluKmUVvRAFEA4KkMYfvRmK0BCvod5-fwJvLpAq59Mqy47M82704PguTwd4gFKpv31446iE86mn1J86eaPfYXGZJp2ZxKjVJwzKO6lTqVXzNo001kkZnAI2uqg69';

//                    $gcm->send($gcm_registration_id, 'contact_update');
                $gcm->send($gcm_registration_id, $push->getPush());


            }

            return $this->redirect(Url::to(['/user/contact']));
        }

        return $this->render('delete', array('model' => $contact));
    }

//    /**
//     * Displays a single contact model.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }
//
//    /**
//     * Creates a new contact model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        $model = new contact();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->contact_id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Updates an existing contact model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->contact_id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
//    }
//
//    /**
//     * Deletes an existing contact model.
//     * If deletion is successful, the browser will be redirected to the 'index' page.
//     * @param integer $id
//     * @return mixed
//     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }
//
//    /**
//     * Finds the contact model based on its primary key value.
//     * If the model is not found, a 404 HTTP exception will be thrown.
//     * @param integer $id
//     * @return contact the loaded model
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    protected function findModel($id)
//    {
//        if (($model = contact::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }
}
