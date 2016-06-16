<?php
namespace humhub\modules\user\controllers;
use humhub\modules\directory\controllers\DirectoryController;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\ProfileField;
use humhub\modules\user\models\Profile;
use Yii;
use yii\helpers\Url;
use humhub\compat\HForm;
use humhub\modules\space\models\forms\InviteForm;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\User;
use humhub\modules\user\models\ContactSearch;
use yii\log\Logger;
use yii\web\Controller;
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
        ]);
    }
    public function actionView()
    {
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id]);
        if ($contact == null) {
            throw new \yii\web\HttpException(404, Yii::t('UserModule.controllers_ContactController', 'Contact not found!'));
        }
        return $this->render('view', array('contact' => $contact));
    }
    public function actionEdit()
    {
        $contact = Contact::findOne(['contact_id' => Yii::$app->request->get('id')]);
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
                $user = User::findOne(['id' => $contact->user_id]);

                $contact->notifyDevice('update');
//                $contact->notifyDevice($user, 'update');
                return $this->redirect(Url::toRoute('/user/contact'));
            }
        }
        if ($form->submitted('delete')) {
            return $this->redirect(Url::toRoute(['/user/contact/delete', 'id' => $contact->contact_id]));
        }
        return $this->render('edit', array('hForm' => $form, 'contact' => $contact));
    }
    public function actionAdd()
    {
        $contactModel = new Contact();
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
        $form->models['Contact'] = $contactModel;
        if ($form->submitted('save') && $form->validate()) {
//            $this->forcePostRequest();
//            $form->models['Contact']->status = User::STATUS_ENABLED;
            if ($form->models['Contact']->save()) {


//                $user = User::findOne(['id' => $contactModel->user_id]);
                $contactModel->notifyDevice('add');
//                $user = User::findOne(['id' => $contactModel->user_id]);
//                $contactModel->notifyDevice($user, 'add');
                return $this->redirect(Url::to(['index']));
            }
        }
        return $this->render('add', array(
            'hForm' => $form,
            'keyword' => $keyword,
            'users' => $searchResultSet->getResultInstances(),
            'pagination' => $pagination
        ));
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
            $contact->delete();
            $user = User::findOne(['id' => $contact->user_id]);
            $contact->notifyDevice('delete');
            return $this->redirect(Url::to(['/user/contact']));
        }
        return $this->render('delete', array('model' => $contact));
    }
    public function actionImport()
    {
        $userSpaces = Membership::findAll(['user_id' => Yii::$app->user->id]);
        $contacts = array();
        $spaces = array();
        foreach ($userSpaces as $space){
            if ($space !== null)
            {
                $spaceId = $space->space_id;
                foreach (Membership::find()->where(['space_id' => $spaceId])->each() as $spaceContact){
                    $userId = $spaceContact->user_id;
                    $existContact = Contact::findOne(['user_id' => Yii::$app->user->id, 'contact_user_id' => $userId]);
                    if ($userId != Yii::$app->user->id && !$existContact){
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
        $contactModel->user_id = Yii::$app->user->id;
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
                'label' => Yii::t('UserModule.controllers_ContactController', 'Save'),
                'class' => 'btn btn-primary',
            )
        );
        $form = new HForm($definition);
        $form->models['Contact'] = $contactModel;
        if ($form->submitted('save') && $form->validate()) {
            if ($form->save()) {
                $contactModel->notifyDevice('add');
                return $this->redirect(Url::toRoute('/user/contact/import'));
            }
        }
        return $this->render('import', array(
            'keyword' => $keyword,
//            'group' => $group,
            'hForm' => $form,
            'model' => $contactModel,
            'users' => $searchResultSet->getResultInstances(),
            'details' => $spaces,
            'pagination' => $pagination
        ));
    }
    public function actionConnect()
    {
        $doit = (int) Yii::$app->request->get('doit');
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id]);
        $userSpaces = Membership::findAll(['user_id' => Yii::$app->user->id]);
        $users = array();
        $spaces = array();
        foreach ($userSpaces as $space){
            if ($space !== null)
            {
                $spaceId = $space->space_id;
                foreach (Membership::find()->where(['space_id' => $spaceId])->each() as $spaceContact){
                    $userId = $spaceContact->user_id;
                    $existContact = Contact::findOne(['user_id' => Yii::$app->user->id, 'contact_user_id' => $userId]);
                    if ($userId != Yii::$app->user->id && !$existContact){
                        $users[] = User::findOne(['id' => $userId]);
                        $spaces[$userId] = $spaceId;
                    }
                }
            }
        }
        foreach (Profile::findAll(['mobile' => $contact->contact_mobile]) as $userProfile) {
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
//            $contact->notifyDevice('connect');
            return $this->redirect(Url::to(['/user/contact']));
        }
        return $this->render('connect', array(
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
        $id = (int) Yii::$app->request->get('id');
        $contact = Contact::findOne(['contact_id' => $id]);
        if ($contact != null) {
            $contact->contact_user_id = null;
            $contact->save();
        }
        return $this->redirect(Url::toRoute(['/user/contact/edit', 'id' => $id]));
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
            array_push($contact_data, $contactInfo);
//            Yii::getLogger()->log(print_r(json_encode($contact->getAttributes(array('user_id', 'contact_user_id', 'nickname'))),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        }
        $contact_list['data'] = $contact_data;
//        ContactInfo::notify($contact_list);

        $gcm = new GCM();
        $user = User::findOne(['id' => $contact_list['data'][0]->user_id]);
        $device = Device::findOne(['device_id' => $user->device_id]);
        $gcm_id = $device->gcmId;
        Yii::getLogger()->log(print_r($contact_list,true),yii\log\Logger::LEVEL_INFO,'MyLog');

//        Yii::getLogger()->log(print_r($contact_list),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm->send($gcm_id, $contact_list);

//        $contact = Contact::find()->where(['user_id' => $user_id])->all();
//        Yii::getLogger()->log(print_r(CJSON::encode(convertModelToArray($contact)),true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        foreach ($contact_list->each() as $contact_user) {
//            $contact_user;
//        }
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
    
    public function activation ($device_id) {
        $user = User::findOne(['device_id' => $device_id]);
        $device = Device::findOne(['device_id' => $device_id]);
        foreach (Contact::find()->where(['contact_user_id' => $user->id])->each() as $contact) {
            $contact->device_phone = $device->phone;
            $contact->save();
        }
        Yii::getLogger()->log(print_r("qweqweqwe",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm = new GCM();
        $gcm_id = $device->gcmId;
//        Yii::getLogger()->log(print_r($gcm_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');

//        Yii::getLogger()->log(print_r($contact_list),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        Yii::getLogger()->log(print_r($this->getUsernamePassword($user),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm->send($gcm_id, $this->getUsernamePassword($user));
    }

    public function getUsernamePassword($user) {
        return [
            'type' => 'active,login',
            'username' => $user->username,
            'password' => $user->temp_password,
        ];
    }
}
