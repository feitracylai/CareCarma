<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\controllers;

use Yii;
use \humhub\components\Controller;
use \yii\helpers\Url;
use yii\log\Logger;
use \yii\web\HttpException;
use \humhub\libs\GCM;
use \humhub\libs\Push;
use \humhub\modules\user\models\User;
use \humhub\modules\user\models\Profile;
use \humhub\modules\user\models\Device;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\forms\SecuritySetting;
use \humhub\modules\user\models\MobileToken;


/**
 * AccountController provides all standard actions for the current logged in
 * user account.
 *
 * @author Luke
 * @package humhub.modules_core.user.controllers
 * @since 0.5
 */
class AccountController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public $subLayout = "@humhub/modules/user/views/account/_layout";

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

    /**
     * Edit Users Profile
     */
    public function actionEdit()
    {

        $user = Yii::$app->user->getIdentity();
//        Yii::getLogger()->log($user->profile, Logger::LEVEL_INFO, 'MyLog');

        // Get Form Definition
        $definition = $user->profile->getFormDefinition();
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'label' => Yii::t('UserModule.controllers_AccountController', 'Save profile'),
                'class' => 'btn btn-primary'
            ),
        );

        $form = new \humhub\compat\HForm($definition, $user->profile);
        $form->showErrorSummary = true;
        if ($form->submitted('save') && $form->validate() && $form->save()) {

            // Trigger search refresh
            $user->save();

            //user in the contacts also change
            $user->updateUserContacts();



            Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_AccountController', 'Saved'));
            return $this->redirect(Url::to(['edit']));
        }

        return $this->render('edit', array('hForm' => $form));
    }


    public function actionEditDevice()
    {
        $user = Yii::$app->user->getIdentity();
        $model = new \humhub\modules\user\models\forms\AccountDevice();
        $model->scenario = 'userDevice';
        $device_list = Device::findAll(['user_id' => $user->id]);

        if ($model->load(Yii::$app->request->post())&& $model->validate()) {

            $device = Device::find()->where(['device_id' => $model->deviceId])->one();
            if ($device==null){
                $model->addError('deviceId', Yii::t('UserModule.controllers_AccountController', "Activation ID is incorrect!"));
            } elseif ($device->user_id != 0) {
                $model->addError('deviceId', Yii::t('UserModule.controllers_AccountController', 'This Activation ID is already in use!'));
            } else {
                $user->device_id = $model->deviceId;
                $device->user_id = $user->getId();
                $user->temp_password = $model->currentPassword;
                $user->save();
                $device->save();
//                $user->updateUserContacts();


                if ($this->checkDevice($device->device_id)) {
                    $this->activationA($device->device_id);
                }


                Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_AccountController', 'Saved'));
            }



        }

        return $this->render('editDevice', array('model' => $model, 'user' => $user, 'device_list' => $device_list));

    }

    public function actionDeleteDevice()
    {
        $todo = (int)Yii::$app->request->get('todo');
        $device_id = Yii::$app->request->get('id');



        if ($todo == 2) {
            $device = Device::findOne(['device_id' => $device_id]);
            if ($device->gcmId != null) {

                $gcm = new GCM();
                $data = array();
                $data['type'] = "deactivate";
                $gcm_registration_id = $device->gcmId;
                $gcm->send($gcm_registration_id, $data);
            }
            $device->delete();

            /***test***/
//            $device->user_id = 0;
//            $device->save();
            /**********/

//            $user->updateUserContacts();



            return $this->redirect(Url::to(['/user/account/edit-device']));
        }


        return $this->render('deleteDevice', array( 'device_id' => $device_id));
    }




    public function checkDevice ($device_id) {
//        $user = User::findOne(['device_id' => $device_id]);
        $device = Device::findOne(['device_id' => $device_id]);
        $user = $device->user_id;
        $gcmId = $device->gcmId;
        if ($user != null and $gcmId != null) {
            return true;
        }
        else {
            return false;
        }
    }




//    public function activation ($device_id) {
//        $user = User::findOne(['device_id' => $device_id]);
//        $device = Device::findOne(['device_id' => $device_id]);
//
//        foreach (Contact::find()->where(['contact_user_id' => $user->id])->each() as $contact) {
//            $contact->device_phone = $device->phone;
//            $contact->save();
//        }
//
//        $gcm = new GCM();
//        $gcm_id = $device->gcmId;
////        Yii::getLogger()->log(print_r($gcm_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
////        Yii::getLogger()->log(print_r($contact_list),true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        $gcm->send($gcm_id, $this->getUsernamePassword($user));
//        $user_new = User::findOne(['device_id' => $device_id]);
//        $user_new->temp_password = null;
//        $user_new->save();
//
//    }

    public function getUsernamePassword($user) {
        $profileImage = new \humhub\libs\ProfileImage($this->getUser()->guid);
        $pos = strpos($profileImage->getUrl(), "?m=");
        $image = substr($profileImage->getUrl(), 0, $pos);
        $profile = Profile::findOne(['user_id' => $user->id]);
        return [
            'type' => 'active,login',
            'fullname' => $profile->firstname . " " . $profile->lastname,
            'username' => $user->username,
            'password' => $user->temp_password,
            'image' => $image,
        ];
    }

    public function actionActivation() {
//        Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $data = Yii::$app->request->post();
//        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm_id = $data['gcm_id'];
        $phone = $data['phone'];
//        Yii::getLogger()->log(print_r($gcm_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        Yii::getLogger()->log(print_r($phone,true),yii\log\Logger::LEVEL_INFO,'MyLog');


        $device = new Device();
        $device_id = "";

        while ($device != null) {
            $device_id = AccountController::randString(4);
            $device = Device::findOne(['device_id' => $device_id]);
        }
        $new_device = new Device();
        $new_device->device_id = $device_id;
        $new_device->gcmId = $gcm_id;
        $new_device->phone = $phone;
        $new_device->save();

        $gcm = new GCM();
        $data2 = array();
        $data2['type'] = "active,device_id";
        $data2['device_id'] = $device_id;
//        Yii::getLogger()->log(print_r($data2,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $gcm->send($gcm_id, $data2);
    }



    public function activationA ($device_id) {
//        $user = User::findOne(['device_id' => $device_id]);
        $device = Device::findOne(['device_id' => $device_id]);
        $user = User::findOne(['id' => $device->user_id]);
//        foreach (Contact::find()->where(['contact_user_id' => $user->id])->each() as $contact) {
//            $contact->device_phone = $device->phone;
//            $contact->save();
//        }
        $gcm = new GCM();
//        Yii::getLogger()->log(print_r($gcm_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');

//        Yii::getLogger()->log(print_r($contact_list),true),yii\log\Logger::LEVEL_INFO,'MyLog');


        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $this->getUsernamePassword($user));
        }
//        $user_new = User::findOne(['device_id' => $device_id]);
//        $user_new->temp_password = null;
//        $user_new->save();
          $user->temp_password = null;
          $user->save();
    }

    public static function randString($length, $specialChars = false) {
        $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ023456789';
        if ($specialChars) {
            $chars .= '!@#$%^&*()';
        }

        $result = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[rand(0, $max)];
        }
        return $result;
    }















    /**
     * Change Account
     *
     * @todo Add Group
     */
    public function actionEditSettings()
    {
        $user = Yii::$app->user->getIdentity();

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

            Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_AccountController', 'Saved'));
        }

        return $this->render('editSettings', array('model' => $model, 'languages' => Yii::$app->params['availableLanguages']));
    }

    /**
     * Allows the user to enable user specifc modules
     */
    public function actionEditModules()
    {
        $user = Yii::$app->user->getIdentity();
        $availableModules = $user->getAvailableModules();

        return $this->render('editModules', array('user' => $user, 'availableModules' => $availableModules));
    }

    public function actionEnableModule()
    {
        $this->forcePostRequest();

        $user = Yii::$app->user->getIdentity();
        $moduleId = Yii::$app->request->get('moduleId');

        if (!$user->isModuleEnabled($moduleId)) {
            $user->enableModule($moduleId);
        }

        return $this->redirect(Url::toRoute('/user/account/edit-modules'));
    }

    public function actionDisableModule()
    {
        $this->forcePostRequest();

        $user = Yii::$app->user->getIdentity();
        $moduleId = Yii::$app->request->get('moduleId');

        if ($user->isModuleEnabled($moduleId) && $user->canDisableModule($moduleId)) {
            $user->disableModule($moduleId);
        }

        return $this->redirect(Url::toRoute('/user/account/edit-modules'));
    }

    /**
     * Delete Action
     *
     * Its only possible if the user is not owner of a workspace.
     */
    public function actionDelete()
    {

        $isSpaceOwner = false;
        $user = Yii::$app->user->getIdentity();

        if ($user->auth_mode != User::AUTH_MODE_LOCAL) {
            throw new HttpException(500, 'This is not a local account! You cannot delete it. (e.g. LDAP)!');
        }

        foreach (\humhub\modules\space\models\Membership::GetUserSpaces() as $space) {
            if ($space->isSpaceOwner($user->id)) {
                $isSpaceOwner = true;
            }
        }

        $model = new \humhub\modules\user\models\forms\AccountDelete;

        if (!$isSpaceOwner && $model->load(Yii::$app->request->post()) && $model->validate()) {

            $user->delete();
//            Yii::$app->user->logout();
            $this->redirect(Yii::$app->homeUrl);
        }

        return $this->render('delete', array(
                    'model' => $model,
                    'isSpaceOwner' => $isSpaceOwner
        ));
    }

    /**
     * Change EMail Options
     *
     * @todo Add Group
     */
    public function actionEmailing()
    {
        $user = Yii::$app->user->getIdentity();
        $model = new \humhub\modules\user\models\forms\AccountEmailing();

        $model->receive_email_activities = $user->getSetting("receive_email_activities", 'core', \humhub\models\Setting::Get('receive_email_activities', 'mailing'));
        $model->receive_email_notifications = $user->getSetting("receive_email_notifications", 'core', \humhub\models\Setting::Get('receive_email_notifications', 'mailing'));
        $model->receive_email_messages = $user->getSetting('receive_email_messages', 'message', \humhub\models\Setting::Get('receive_email_messages', 'mailing'));
        $model->enable_html5_desktop_notifications = $user->getSetting("enable_html5_desktop_notifications", 'core', \humhub\models\Setting::Get('enable_html5_desktop_notifications', 'notification'));

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            $user->setSetting("receive_email_activities", $model->receive_email_activities);
//            $user->setSetting("receive_email_notifications", $model->receive_email_notifications);
            $user->setSetting("receive_email_messages", $model->receive_email_messages);
            $user->setSetting('enable_html5_desktop_notifications', $model->enable_html5_desktop_notifications);

            Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_AccountController', 'Saved'));
        }
        return $this->render('emailing', array('model' => $model));
    }

    /**
     * Change Current Password
     *
     */
    public function actionChangeEmail()
    {
        $user = Yii::$app->user->getIdentity();
        if ($user->auth_mode != User::AUTH_MODE_LOCAL) {
            throw new HttpException(500, Yii::t('UserModule.controllers_AccountController', 'You cannot change your e-mail address here.'));
        }

        $model = new \humhub\modules\user\models\forms\AccountChangeEmail;
        $model->scenario = 'userEmail';

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->sendChangeEmail()) {
            $user->updateUserContacts();

            return $this->render('changeEmail_success', array('model' => $model));
        }

        return $this->render('changeEmail', array('model' => $model));
    }

    /**
     * After the user validated his email
     *
     */
    public function actionChangeEmailValidate()
    {
        $user = Yii::$app->user->getIdentity();

        if ($user->auth_mode != User::AUTH_MODE_LOCAL) {
            throw new CHttpException(500, Yii::t('UserModule.controllers_AccountController', 'You cannot change your e-mail address here.'));
        }

        $token = Yii::$app->request->get('token');
        $email = Yii::$app->request->get('email');

        // Check if Token is valid
        if (md5(\humhub\models\Setting::Get('secret') . $user->guid . $email) != $token) {
            throw new HttpException(404, Yii::t('UserModule.controllers_AccountController', 'Invalid link! Please make sure that you entered the entire url.'));
        }

        // Check if E-Mail is in use, e.g. by other user
        $emailAvailablyCheck = \humhub\modules\user\models\User::findOne(['email' => $email]);
        if ($emailAvailablyCheck != null) {
            throw new HttpException(404, Yii::t('UserModule.controllers_AccountController', 'The entered e-mail address is already in use by another user.'));
        }



        $user->email = $email;
        $user->save();


        return $this->render('changeEmailValidate', array('newEmail' => $email));
    }

    /**
     * Change users current password
     */
    public function actionChangePassword()
    {
        $user = Yii::$app->user->getIdentity();

        if ($user->auth_mode != User::AUTH_MODE_LOCAL) {
            throw new CHttpException(500, Yii::t('UserModule.controllers_AccountController', 'You cannot change your e-mail address here.'));
        }

        $userPassword = new \humhub\modules\user\models\Password();
        $userPassword->scenario = 'changePassword';

        if ($userPassword->load(Yii::$app->request->post()) && $userPassword->validate()) {
            $userPassword->user_id = Yii::$app->user->id;
            $userPassword->setPassword($userPassword->newPassword);
            $userPassword->save();

            return $this->render('changePassword_success');
        }

        return $this->render('changePassword', array('model' => $userPassword));
    }

    /**
     * Crops the banner image of the user
     */
    public function actionCropBannerImage()
    {
        $model = new \humhub\models\forms\CropProfileImage();
        $profileImage = new \humhub\libs\ProfileBannerImage($this->getUser()->guid);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $profileImage->cropOriginal($model->cropX, $model->cropY, $model->cropH, $model->cropW);
            return $this->htmlRedirect($this->getUser()->getUrl());
        }

        return $this->renderAjax('cropBannerImage', ['model' => $model, 'profileImage' => $profileImage, 'user' => $this->getUser()]);
    }

    /**
     * Handle the banner image upload
     */
    public function actionBannerImageUpload()
    {
        \Yii::$app->response->format = 'json';

        $model = new \humhub\models\forms\UploadProfileImage();
        $json = array();

        $files = \yii\web\UploadedFile::getInstancesByName('bannerfiles');
        $file = $files[0];
        $model->image = $file;

        if ($model->validate()) {
            $profileImage = new \humhub\libs\ProfileBannerImage($this->getUser()->guid);
            $profileImage->setNew($model->image);

            $json['error'] = false;
            $json['name'] = "";
            $json['url'] = $profileImage->getUrl();
            $json['size'] = $model->image->size;
            $json['deleteUrl'] = "";
            $json['deleteType'] = "";
        } else {
            $json['error'] = true;
            $json['errors'] = $model->getErrors();
        }

        return ['files' => $json];
    }

    /**
     * Handle the profile image upload
     */
    public function actionProfileImageUpload()
    {
        \Yii::$app->response->format = 'json';
        $model = new \humhub\models\forms\UploadProfileImage();
        $json = array();

        $files = \yii\web\UploadedFile::getInstancesByName('profilefiles');
        $file = $files[0];
        $model->image = $file;
        $user = Yii::$app->user->getIdentity();

        if ($model->validate()) {

            $json['error'] = false;

            $profileImage = new \humhub\libs\ProfileImage($this->getUser()->guid);
            $profileImage->setNew($model->image);

            $json['name'] = "";
            $json['url'] = $profileImage->getUrl();
//            Yii::getLogger()->log(print_r($profileImage->getUrl(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
            $json['size'] = $model->image->size;
            $json['deleteUrl'] = "";
            $json['deleteType'] = "";

            $user->updateUserContacts();


        } else {
            $json['error'] = true;
            $json['errors'] = $model->getErrors();
        }
        return array('files' => $json);
    }

    /**
     * Crops the profile image of the user
     */
    public function actionCropProfileImage()
    {
        $model = new \humhub\models\forms\CropProfileImage();
        $profileImage = new \humhub\libs\ProfileImage($this->getUser()->guid);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $profileImage->cropOriginal($model->cropX, $model->cropY, $model->cropH, $model->cropW);
            return $this->htmlRedirect($this->getUser()->getUrl());
        }

        return $this->renderAjax('cropProfileImage', array('model' => $model, 'profileImage' => $profileImage, 'user' => $this->getUser()));
    }

    /**
     * Deletes the profile image or profile banner
     */
    public function actionDeleteProfileImage()
    {
        \Yii::$app->response->format = 'json';

        $this->forcePostRequest();

        $type = Yii::$app->request->get('type', 'profile');

        $json = array('type' => $type);

        $image = null;
        if ($type == 'profile') {
            $image = new \humhub\libs\ProfileImage($this->getUser()->guid);
        } elseif ($type == 'banner') {
            $image = new \humhub\libs\ProfileBannerImage($this->getUser()->guid);
        } elseif ($type == 'background'){
            $image = new \humhub\libs\BackgroundImage($this->getUser()->guid);
        }

        if ($image) {
            $image->delete();
            $json['defaultUrl'] = $image->getUrl();
        }

        return $json;
    }




    /**
     * Returns the current user of this account
     * 
     * An administration can also pass a user id via GET parameter to change users
     * accounts settings.
     * 
     * @return User the user
     */
    public function getUser()
    {
        if (Yii::$app->request->get('userGuid') != '' && Yii::$app->user->getIdentity()->super_admin === 1) {
            $user = User::findOne(['guid' => Yii::$app->request->get('userGuid')]);
            if ($user === null) {
                throw new HttpException(404, 'Could not find user!');
            }
            return $user;
        }

        return Yii::$app->user->getIdentity();
    }

    public function actionBackground(){
        $user = User::findOne(['id' => Yii::$app->user->id]);

        $imageName = $_POST['image'];
        $background = './uploads/background/'.$imageName;

        if ($user->background == $background){
            $user->background = null;
        } else {
            $image = new \humhub\libs\BackgroundImage($this->getUser()->guid);
            if ($image) {
                $image->delete();
            }

            $user->background = $background;

        }
        $user->save();

        return;
    }

    public function actionBackgroundImageUpload()
    {
        \Yii::$app->response->format = 'json';

        $model = new \humhub\models\forms\UploadProfileImage();

        $json = array();

        $files = \yii\web\UploadedFile::getInstancesByName('backgroundfiles');
        $file = $files[0];
        $model->image = $file;

        if ($model->validate()) {

            $json['error'] = false;

            $backgroundImage = new \humhub\libs\BackgroundImage($this->getUser()->guid);
            $backgroundImage->setNew($model->image);

            $json['name'] = "";
            $json['url'] = $backgroundImage->getUrl();
            $json['size'] = $model->image->size;
            $json['deleteUrl'] = "";
            $json['deleteType'] = "";

            $user = User::findOne(['id' => Yii::$app->user->id]);
            $user->background = null;
            $user->save();
        } else {
            $json['error'] = true;
            $json['errors'] = $model->getErrors();
        }

        return array('files' => $json);
    }

    public function actionThemeSave(){
        Yii::$app->response->format = 'json';
        $theme = $_POST['data'];


        $user = User::findOne(['id' => Yii::$app->user->id]);
        if ($user!= null){
            if ($user->theme != null && $user->theme != $theme){
                $old = $user->theme;
            } else {
                $old = null;
            }
            $user->theme = $theme;
        }

        $user->save();

        return [
            'success' => true,
            'old' => $old,
        ];
    }

    public function actionPrivacy()
    {

        $user = Yii::$app->user->getIdentity();
        $model = new SecuritySetting();

        $model->contact_notify_setting = $user->getSetting("contact_notify_setting", 'contact', \humhub\models\Setting::Get('contact_notify_setting', 'send'));
        $model->view_about_page = $user->getSetting("view_about_page", 'profile', \humhub\models\Setting::Get('view_about_page', 'private'));

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->setSetting("contact_notify_setting", $model->contact_notify_setting, 'contact');
            $user->setSetting("view_about_page", $model->view_about_page, 'profile');


            Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_ContactController', 'Saved'));
        }

        return $this->render('privacy', array(
            'model' => $model
        ));
    }

    public function actionDeleteToken()
    {
        $data = Yii::$app->request->post();
        $token = $data["token"];
        $mt = MobileToken::findOne(['device_token' => $token]);
        if ($mt != null) {
            $mt->delete();
        }

    }
}

?>
