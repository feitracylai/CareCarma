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
use humhub\modules\user\models\Group;
use humhub\modules\user\models\Device;
use humhub\modules\user\models\Contact;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\space\modules\manage\models\DeviceUserSearch;
use humhub\compat\HForm;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\space\modules\manage\components\Controller;
/**
 * Member Controller
 *
 * @author Luke
 */
class DeviceController extends ContentContainerController
{
    /**
     * CareReceiver Administration Action
     */

    public $hideSidebar = true;

    public function actionIndex()
    {
        $space = $this->getSpace();
        $searchModel = new DeviceUserSearch();
        $searchModel->space_id = $space->id;
        $searchModel->status = Membership::STATUS_MEMBER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', array(
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'space' => $space
        ));
    }
    public function actionAdd()
    {


        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Family Administrator only!');

        $userModel = new User();
        $userModel->scenario = 'editCare';
        $deviceModel = new \humhub\modules\user\models\forms\AccountDevice();
        $userPasswordModel = new Password();
        $userPasswordModel->scenario = 'registration';
        $profileModel = $userModel->profile;
        $profileModel->scenario = 'registration';
        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();
        // Add User Form
        $definition['elements']['User'] = array(
            'type' => 'form',
            'title' => Yii::t('SpaceModule.controllers_DeviceController', 'Account'),
            'elements' => array(
                'username' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 25,
                    'title' => 'Pick a username for Care Receiver in this system. You can use letters, numbers, and periods. Between 4 to 25 characters.',
                ),
                'email' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 100,
                    'title' => 'Use a common email address of this Care Receiver that can be used to log in this system and receive notifications from this system'
                ),
                'device_id'=> array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 45,
                    'title' => 'Check the Activation # on Cosmos.'
                ),
            ),
        );
        // Add User Password Form
        $definition['elements']['UserPassword'] = array(
            'type' => 'form',
            #'title' => 'Password',
            'elements' => array(
                'newPassword' => array(
                    'type' => 'password',
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'minlength' => 8,
                    'title' => 'Use at least 8 characters. This Care Receiver can use it to log in this system.'
                ),
                'newPasswordConfirm' => array(
                    'type' => 'password',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
            ),
        );
        // Add Profile Form
        $definition['elements']['Profile'] = array_merge(array('type' => 'form'), $profileModel->getFormDefinition());
        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'label' => Yii::t('SpaceModule.controllers_DeviceController', 'Create account'),
            ),
        );
        $form = new HForm($definition);
        $form->models['User'] = $userModel;
        $form->models['UserPassword'] = $userPasswordModel;
        $form->models['Profile'] = $profileModel;
        if ($form->submitted('save') && $form->validate()) {
            $this->forcePostRequest();
            $form->models['User']->status = User::STATUS_ENABLED;
            $device = Device::find()->where(['device_id' => $form->models['User']->device_id])->one();
            if ($device != null || $form->models['User']->device_id == '') {
                if ($form->models['User']->save()) {
                    // Save User Profile
                    // save the temp_password
                    $user_current = User::findOne(['id' => $userModel->id]);
                    $user_current->temp_password = $userPasswordModel->newPassword;
                    $user_current->save();
                    $form->models['Profile']->user_id = $form->models['User']->id;
                    $form->models['Profile']->save();
                    // Save User Password
                    $form->models['UserPassword']->user_id = $form->models['User']->id;
                    $form->models['UserPassword']->setPassword($form->models['UserPassword']->newPassword);
                    $form->models['UserPassword']->save();
                    // Add memeber's infomation in his/her Contacts
                    $memebers = Membership::findAll(['space_id' => $space->id]);
                    foreach ($memebers as $memeber) {
                        if ($memeber->user_id != $form->models['User']->id && $memeber->status == 3) {
                            $contact_user = User::findOne(['id' => $memeber->user_id]);
                            $contact = new Contact();
                            $contact->contact_user_id = $contact_user->id;
                            $contact->contact_first = $contact_user->profile->firstname;
                            $contact->contact_last = $contact_user->profile->lastname;
                            $contact->contact_mobile = $contact_user->profile->mobile;
                            $contact->contact_email = $contact_user->email;
                            $contact->home_phone = $contact_user->profile->phone_private;
                            $contact->work_phone = $contact_user->profile->phone_work;
                            if ($contact_user->device_id != null) {
                                $contact->device_phone = $contact_user->device->phone;
                            }
                            $contact->user_id = $form->models['User']->id;
                            $contact->save();
                        }
                    }
                    // Become Care Receiver in this space
                    $space->addMember($form->models['User']->id);
                    $space->setCareReceiver($form->models['User']->id);
                    // check if device fulfill all the rule of activation, if yes, activation
                    if ($this->checkDevice($form->models['User']->device_id)) {
                        $this->activation($form->models['User']->device_id);
                    }
                    return $this->redirect($space->createUrl('/space/manage/device'));
                }
            }
            else {
                $form->models['User']->addError('device_id', 'Invalid input! Please make sure that you entered the correct device ID.');
            }
        }
        return $this->render('add', array(
            'hForm' => $form,
            'space' => $space
        ));
    }
    public function checkDevice ($device_id) {
        $user = User::findOne(['device_id' => $device_id]);
        $device = Device::findOne(['device_id' => $device_id]);
        if ($device != null){
            $gcmId = $device->gcmId;
            if ($user != null and $gcmId != null) {
                return true;
            }
            else {
                return false;
            }
        } else {
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
        $gcm = new GCM();
        $gcm_id = $device->gcmId;
        $gcm->send($gcm_id, $this->getUsernamePassword($user));
        $user_new = User::findOne(['device_id' => $device_id]);
        $user_new->temp_password = null;
        $user_new->save();
    }
    public function getUsernamePassword($user) {
        return [
            'type' => 'active,login',
            'username' => $user->username,
            'password' => $user->temp_password,
        ];
    }
    public function actionEdit() {
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Family Administrator only!');

        $user =  $this->getCare();
        $emailModel = new \humhub\modules\user\models\forms\AccountChangeEmail;
        if ($emailModel->load(Yii::$app->request->post()) && $emailModel->validate() && $emailModel->sendChangeEmail()) {
            Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));
//                    return $this->render('changeEmail_success', array('model' => $emailModel));
        }
        return $this->render('edit', array(
            'emailModel' => $emailModel,
            'user' => $user,
            'space' => $space,
        ));
    }
    public function actionDevice(){
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Family Administrator only!');

        $user =  $this->getCare();
        $deviceOld = Device::findOne(['device_id' => $user->device_id]);
        $deviceModel = new \humhub\modules\user\models\forms\AccountDevice();
        if ($deviceModel->load(Yii::$app->request->post())&& $deviceModel->validate()) {
            $device = Device::find()->where(['device_id' => $deviceModel->deviceId])->one();
            if ($device!=null) {
                if ($device != $deviceOld) {
                    $user->device_id = $deviceModel->deviceId;
                    if ($device->gcmId != null ) {
                        $gcm = new GCM();
                        $push = new Push();
                        $push->setTitle('user id');
                        $push->setData($user->getId());
                        $gcm_registration_id = $device->gcmId;
                        $gcm->send($gcm_registration_id, $push->getPush());
                    }
                    if($deviceOld != null) {
                        $gcmOld = new GCM();
                        $pushOld = new Push();
                        $pushOld->setTitle('user');
                        $pushOld->setData('delete device');
                        $gcmOld->send($deviceOld->gcmId, $pushOld->getPush());
                    }
                    $user->save();
                    $user->updateUserContacts();
                }
                Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));
            }
            else {
                $deviceModel->addError('deviceId', 'Invalid input! Please make sure that you entered the correct device ID.');
            }
        }
        return $this->render('device', array(
            'model' => $deviceModel,
            'user' => $user,
            'space' => $space,
        ));
    }
    public function actionProfile() {
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Family Administrator only!');

        $user = $this->getCare();
        // Get Form Definition
        $user->profile->scenario = 'editCare';
        $definition = $user->profile->getFormDefinition();
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'label' => Yii::t('SpaceModule.controllers_DeviceController', 'Save profile'),
                'class' => 'btn btn-primary'
            ),
        );
        $form = new \humhub\compat\HForm($definition, $user->profile);
        $form->showErrorSummary = true;
        if ($form->submitted('save') && $form->validate() && $form->save()) {
            // Trigger search refresh
            $user->save();
            $user->updateUserContacts();
            Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));
            return $this->redirect(Url::to(['edit']));
        }
        return $this->render('profile', array(
            'hForm' => $form,
            'space' => $space,
            'user' => $user,
        ));
    }
    public function actionSettings() {
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Family Administrator only!');

        $user = $this->getCare();
        $model = new \humhub\modules\user\models\forms\AccountSettings();
        $model->language = $user->language;
        if ($model->language == "") {
            $model->language = \humhub\models\Setting::Get('defaultLanguage');
        }
        $model->timeZone = $user->time_zone;
        if ($model->timeZone == "") {
            $model->timeZone = \humhub\models\Setting::Get('timeZone');
        }
        $model->show_introduction_tour = $user->getSetting("hideTourPanel", "tour");
        $model->tags = $user->tags;
        $model->show_introduction_tour = $user->getSetting("hideTourPanel", "tour");
        $model->visibility = $user->visibility;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setSetting('hideTourPanel', $model->show_introduction_tour, "tour");
            $user->language = $model->language;
            $user->tags = $model->tags;
            $user->time_zone = $model->timeZone;
            $user->visibility = $model->visibility;
            $user->save();
            Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));
        }
        return $this->render('settings', array(
            'space' => $space,
            'user' => $user,
            'model' => $model,
            'languages' => Yii::$app->params['availableLanguages'
            ]));
    }
    public function actionDelete()
    {
        $isSpaceOwner = false;
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Family Administrator only!');

        $user = $this->getCare();
        if ($user->auth_mode != User::AUTH_MODE_LOCAL) {
            throw new HttpException(500, 'This is not a local account! You cannot delete it. (e.g. LDAP)!');
        }
        foreach (\humhub\modules\space\models\Membership::GetUserSpaces() as $spaces) {
            if ($spaces->isSpaceOwner($user->id)) {
                $isSpaceOwner = true;
            }
        }
        $model = new \humhub\modules\user\models\forms\AccountDelete;
        if (!$isSpaceOwner && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->delete();
            $this->redirect($space->createUrl('/space/manage/device/index'));
        }
        return $this->render('delete', array(
            'space' => $space,
            'model' => $model,
            'user' => $user,
            'isSpaceOwner' => $isSpaceOwner
        ));
    }
    public function actionReport() {
        $space = $this->getSpace();
        $user = $this->getCare();
        return $this->render('report', array(
            'space' => $space,
            'user' => $user
        ));
    }
    public function actionRemove()
    {
//        $this->forcePostRequest();
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Family Administrator only!');

        $userGuid = Yii::$app->request->get('userGuid');
        $user = User::findOne(array('guid' => $userGuid));
        $space->setMember($user->id);
        // Redirect  back to Administration page
        return $this->htmlRedirect($space->createUrl('/space/manage/device', ['sguid' => $space->guid]));
    }
    public function getCare(){
        return User::findOne(['guid' => Yii::$app->request->get('rguid')]);
    }
}
?>