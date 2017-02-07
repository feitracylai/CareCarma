<?php
namespace humhub\modules\space\modules\manage\controllers;
use humhub\modules\admin\models\Log;
use humhub\modules\devices\models\Classlabelshourheart;
use humhub\modules\devices\models\Classlabelshoursteps;
use humhub\modules\space\modules\manage\models\MembershipSearch;
use humhub\modules\user\models\Classlabels;
use humhub\modules\user\models\forms\AccountDevice;
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
use \humhub\modules\user\models\Profile;
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
            throw new HttpException(403, 'Access denied - Circle Administrator only!');


        $userModel = new User();
        $userModel->scenario = 'editCare';
        $deviceModel = new AccountDevice();
        $deviceModel->scenario = 'editDevice';
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
//        //Activate the device
//        $definition['elements']['Device'] = array(
//            'type' => 'form',
//            'elements' => array(
//                'deviceId' => array(
//                    'type' => 'text',
//                    'class' => 'form-control',
//                    'maxlength' => 45,
//                    'title' => 'Activate CoSMoS device or CoSMoS app.'
//                )
//            ),
//        );
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
//        $form->models['Device'] = $deviceModel;
        $form->models['Profile'] = $profileModel;

        if ($form->submitted('save') && $form->validate()) {
            $this->forcePostRequest();
            $form->models['User']->status = User::STATUS_ENABLED;
//            $device = Device::find()->where(['device_id' => $form->models['User']->device_id])->one();
//            if ($device != null || $form->models['User']->device_id == '') {
                if ($form->models['User']->save()) {

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
                            $user = User::findOne(['id' => $form->models['User']->id]);
                            $user->addContact($contact_user);


                        }
                    }
                    // Become Care Receiver in this space
                    $space->addMember($form->models['User']->id);
                    $space->setCareReceiver($form->models['User']->id);
                    // check if device fulfill all the rule of activation, if yes, activation
                    if (!empty($form->models['User']->device_id)){
                        if ($this->checkDevice($form->models['User']->device_id)) {
                            $device = Device::findOne(['device_id' => $form->models['User']->device_id]);
                            $device->user_id = $user->id;
                            $device->save();
                            $this->activation($form->models['User']->device_id);
                        }
                    }

                    return $this->redirect($space->createUrl('/space/manage/device'));
                }
//            }
//            else {
//                $form->models['User']->addError('device_id', 'Invalid input! Please make sure that you entered the correct device ID.');
//            }
        }
        return $this->render('add', array(
            'hForm' => $form,
            'space' => $space,

        ));
    }

    public function actionAddCare()
    {
        $space = $this->getSpace();
        $doit = Yii::$app->request->get('doit');

        $searchModel = new MembershipSearch();
        $searchModel->space_id = $space->id;
        $searchModel->status = Membership::STATUS_MEMBER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $users = array();
        $members = Membership::findAll(['space_id' => $space->id]);
        foreach ($members as $member)
        {
            if ($member->group_id != $space::USERGROUP_MODERATOR && !$space->isSpaceOwner($member->user_id) && $member->status == 3)
            {
                $user = User::findOne(['id' => $member->user_id]);
                $users[] = $user;
            }
        }

//        Yii::getLogger()->log($space->created_by, Logger::LEVEL_INFO, 'MyLog');
        if ($doit == 2)
        {
            $userId = Yii::$app->request->get('linkId');
            $membership = Membership::findOne(['space_id' => $space->id, 'user_id' => $userId]);
            $sender = User::findOne(['id' => Yii::$app->user->id]);

            $space->addCare($sender, $userId);

            return $this->redirect($space->createUrl('device/add-care'));
        }


        return $this->render('add-care', array(
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'space' => $space,
            'users' => $users
        ));
    }

    public function actionAddRemind()
    {
        $space = $this->getSpace();
        $userId = Yii::$app->request->get('linkId');



        if ($space->isOtherCareReceiver($userId)){
            return $this->renderAjax('add-remind', ['status' => 'care']);
        } elseif ($space->isAdmin($userId)) {
            return $this->renderAjax('add-remind', ['status' => 'admin', 'space' => $space, 'userId' => $userId]);
        } else {
            return $this->redirect($space->createUrl('device/add-care'));
        }



//        return $this->renderAjax('care-remind');
    }


    public function actionCareRemind()
    {
        $space = $this->getSpace();
        $userId = Yii::$app->user->id;


        if ($space->isAdmin($userId))
        {
            return $this->renderAjax('care-remind', ['status' => 'thisAdmin', 'space' => $space]);
        } else {
            return $this->renderAjax('care-remind', ['status' => 'others', 'space' => $space]);
        }
    }

    public function actionCareAccepted()
    {
        $space = $this->getSpace();

        $careUser = User::findOne(['id' => Yii::$app->user->id]);

        $space->acceptCare($careUser);


        return $this->redirect($space->createUrl('/space/space'));
    }

    public function actionCareDenied()
    {
        $space = $this->getSpace();

        $careUser = User::findOne(['id' => Yii::$app->user->id]);
        $space->denyCare($careUser);

        return $this->redirect(Url::home());
    }

    public function actionAddCareCancel()
    {
        $space = $this->getSpace();
        $careUser = User::findOne(['id' => Yii::$app->request->get('linkId')]);
        $space->cancelAdd($careUser);

        return $this->redirect($space->createUrl('device/add-care'));
    }

    public function checkDevice ($device_id) {
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

    public function activation ($device_id) {

        $device = Device::findOne(['device_id' => $device_id]);
        $user = User::findOne(['id' => $device->user_id]);

        $gcm = new GCM();
        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $this->getUsernamePassword($user));
        }
        $user->temp_password = null;
        $user->save();

        $device->activate = 1;
        $device->save();
    }

    public function getUsernamePassword($user) {

        $profileImage = new \humhub\libs\ProfileImage($user->guid);
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



    public function actionProfile() {
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Circle Administrator only!');

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
//            return $this->redirect(Url::to(['profile']));
        }
        return $this->render('profile', array(
            'hForm' => $form,
            'space' => $space,
            'user' => $user,
        ));
    }

    public function actionEdit() {
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Circle Administrator only!');

        $user =  $this->getCare();
        $emailModel = new \humhub\modules\user\models\forms\AccountChangeEmail;
        if ($emailModel->load(Yii::$app->request->post()) && $emailModel->validate() && $emailModel->sendChangeEmail()) {
            $user->email = $emailModel->newEmail;
            $user->save();

            Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));
//                    return $this->render('changeEmail_success', array('model' => $emailModel));
        }
        return $this->render('edit', array(
            'emailModel' => $emailModel,
            'user' => $user,
            'space' => $space,
        ));
    }

    private function checkReceiverDevice($model, $receiver){
        $check = true;
        $device = Device::find()->where(['device_id' => $model->deviceId])->one();
        if ($receiver->currentPassword !== null && !$receiver->currentPassword->validatePassword($model->currentPassword)) {
            $model->addError('currentPassword', Yii::t('SpaceModule.controllers_DeviceController', "Your password is incorrect!"));
            $check = false;
        }
        if ($device == null || $device->activate == 1){
            $model->addError('deviceId',  Yii::t('SpaceModule.controllers_DeviceController', "Activation ID is incorrect!"));
            $check = false;
        }
        /****if someone use the same device now****/
        $same_device_other_user = Device::find()->where(['hardware_id' => $device->hardware_id, 'activate' => 1])->andWhere(['<>','user_id', $receiver->getId()])->one();
        if($same_device_other_user != null){
            $model->addError('deviceId', Yii::t('SpaceModule.controllers_DeviceController', "Activation ID is incorrect!"));
            $check = false;
        }
        return $check;
    }

    public function actionDevice(){
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Circle Administrator only!');

        $user =  $this->getCare();
        $device_list = Device::findAll(['user_id' => $user->id, 'activate' => 1]);

        $deviceModel = new \humhub\modules\user\models\forms\AccountDevice();
        $deviceModel->scenario = 'editDevice';

        if ($deviceModel->load(Yii::$app->request->post())&& $deviceModel->validate() && $this->checkReceiverDevice($deviceModel, $user)) {
            $device = Device::find()->where(['device_id' => $deviceModel->deviceId])->one();


            $user->temp_password = $deviceModel->currentPassword;
            $user->save();

            /****if it is the previous same device, replace the older row.****/
            $same_device = Device::findOne(['hardware_id' => $device->hardware_id, 'user_id' => $user->getId()]);
            if (!empty($device->hardware_id) && !empty($same_device) && $same_device->id != $device->id){
                $same_device->device_id = $device->device_id;
                $same_device->gcmId = $device->gcmId;
                $same_device->phone = $device->phone;
                $same_device->type = $device->type;
                $same_device->model = $device->model;
                $device->delete();
                $same_device->save();

            } else {
                $device->user_id = $user->getId();
                $device->save();

            }



                    // check if device fulfill all the rule of activation, if yes, activation
                if ($this->checkDevice($deviceModel->deviceId)) {
                    $this->activation($deviceModel->deviceId);
                }


//                    $user->updateUserContacts();

            Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));

            return $this->redirect($space->createUrl('device',['rguid' => $user->guid]));
        }
        return $this->render('device', array(
            'model' => $deviceModel,
            'user' => $user,
            'space' => $space,
            'device_list' => $device_list,
        ));
    }

    public function actionDeleteDevice()
    {

        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Circle Administrator only!');

        $user = $this->getCare();
        $device = Device::findOne(['device_id' => Yii::$app->request->get('id')]);
        $doit = (int) Yii::$app->request->get('doit');


        if ($doit == 2) {


            if ($device->gcmId != null) {

                $gcm = new GCM();
                $data = array();
                $data['type'] = "deactivate";
                $gcm_registration_id = $device->gcmId;
                $gcm->send($gcm_registration_id, $data);
            }
            $device->activate = 0;
            $device->save();


            /***test***/
//            $device->user_id = 0;
//            $device->save();
            /**********/
//            $user->updateUserContacts();



            return $this->redirect($space->createUrl('device',['rguid' => $user->guid]));
        }


        return $this->render('deleteDevice', array('device' => $device, 'user' => $user, 'space' => $space));
    }

//    public function actionAccountSettings()
//    {
//        $space = $this->getSpace();
//        if (!$space->isAdmin())
//            throw new HttpException(403, 'Access denied - Circle Administrator only!');
//
//        $user =  $this->getCare();
//        $emailModel = new \humhub\modules\user\models\forms\AccountChangeEmail;
//        if ($emailModel->load(Yii::$app->request->post()) && $emailModel->validate() && $emailModel->sendChangeEmail()) {
//            $user->email = $emailModel->newEmail;
//            $user->save();
//            $community_user = Users::findOne(['id' => $user->id]);
//            $community_user->email = $user->email;
//            $community_user->save();
//            Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));
//        }
//
//        $deviceOld = Device::findOne(['device_id' => $user->device_id]);
//        $deviceModel = new \humhub\modules\user\models\forms\AccountDevice();
//        if ($deviceModel->load(Yii::$app->request->post())&& $deviceModel->validate()) {
//            $device = Device::find()->where(['device_id' => $deviceModel->deviceId])->one();
//            if ($device!=null) {
//                if ($device != $deviceOld) {
//                    $user->device_id = $deviceModel->deviceId;
//                    $user->save();
//
//                    // check if device fulfill all the rule of activation, if yes, activation
//                    if ($this->checkDevice($deviceModel->deviceId)) {
//                        $this->activation($deviceModel->deviceId);
//                    }
//                    if($deviceOld != null) {
//                        $gcmOld = new GCM();
//                        $pushOld = new Push();
//                        $pushOld->setTitle('user');
//                        $pushOld->setData('delete device');
//                        $gcmOld->send($deviceOld->gcmId, $pushOld->getPush());
//                    }
//
//                    $user->updateUserContacts();
//                }
//                Yii::$app->getSession()->setFlash('data-saved', Yii::t('SpaceModule.controllers_DeviceController', 'Saved'));
//            }
//            else {
//                $deviceModel->addError('deviceId', 'Invalid input! Please make sure that you entered the correct device ID.');
//            }
//        }
//        return $this->render('account-settings', array(
//            'model' => $deviceModel,
//            'emailModel' => $emailModel,
//            'user' => $user,
//            'space' => $space,
//        ));
//    }

    public function actionSettings() {
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Circle Administrator only!');

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
            throw new HttpException(403, 'Access denied - Circle Administrator only!');

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

        $dataDevices = Device::find()->where(['user_id' => $user->id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->all();
        if (!$dataDevices){
            return $this->render('report-none', array(
                'space' => $space,
                'user' => $user,
            ));
        }

        $today = date("Y-m-d");
        date_default_timezone_set("GMT");
        $unixtoday = strtotime($today);
        $unixlastweek = strtotime('-1 week', $unixtoday);
        $start = $unixlastweek."000";
        $end = $unixtoday. "000";

        $basicData = array_fill(0, 7, array_fill(0, 8, 0));
        $basicData0 = ['Month', '0:00 -- 4:00', '4:00 -- 8:00', '8:00 -- 12:00', '12:00 -- 16:00', '16:00 -- 20:00', '20:00 -- 24:00', ['role' => 'annotation']];
//        $basicData = array_fill(0, 7, array_fill(0, 14, 0));
//        $basicData0 = ['Month', '0:00', '2:00', '4:00', '6:00',  '8:00', '10:00',
//            '12:00', '14:00', '16:00', '18:00', '20:00', '22:00', ['role' => 'annotation']];
        array_unshift($basicData, $basicData0);

        $time = $unixlastweek;
        for ($i = 1; $i < 8; $i++){
            $basicData[$i][0] = date('M d', $time);
            $time = $time + 86400;
        }


        $DATA = array();
        $devices = array();
        $yesterday_step = 0;
        $count = 0;
        foreach ($dataDevices as $dataDevice){
            $deviceReportData = $basicData;
            $steps_data = Classlabelshoursteps::find()->where(['hardware_id' => $dataDevice->hardware_id])
                ->andWhere(['>=', 'time', $start])->andWhere(['<', 'time', $end])->all();
            if ($steps_data){
                foreach ($steps_data as $hourlyrow){
                    $hourlystep = $hourlyrow->stepsLabel;
                    $hourlytime = substr($hourlyrow->time, 0, 10) + 1; //division will have remainder

                    $intervaltime = $hourlytime - $unixlastweek;
                    $row = (int)($intervaltime/86400) + 1; //which day
                    $remainder = $intervaltime - ($row - 1) * 86400;
                    $column = (int)($remainder/14400) + 1; //which hour section

                    $deviceReportData[$row][$column] = $deviceReportData[$row][$column] + $hourlystep;
                    $deviceReportData[$row][7] = $deviceReportData[$row][7] + $hourlystep;

//                    $intervaltime = $hourlytime - $unixlastweek;
//                    $row = (int)($intervaltime/86400) + 1; //which day
//                    $remainder = $intervaltime - ($row - 1) * 86400;
//                    $column = (int)($remainder/7200) + 1; //which hour section
//
//                    $deviceReportData[$row][$column] = $deviceReportData[$row][$column] + $hourlystep;
//                    $deviceReportData[$row][13] = $deviceReportData[$row][13] + $hourlystep;
                }
            }

            $yesterday_step = $yesterday_step + $deviceReportData[7][7];
//            $yesterday_step = $yesterday_step + $deviceReportData[7][13];
            $DATA[$count] = $deviceReportData;
            $devices[$count] = $dataDevice;
            $count++;

            $lastData = Classlabelshoursteps::find()->where(['hardware_id' => $dataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
            if (!is_null($lastData)){
                $lastData->seen = 1;
                $lastData->save();
            }
        }


        return $this->render('report', array(
            'space' => $space,
            'user' => $user,
            'data' => $DATA,
            'devices' => $devices,
            'yesterdayStep' => $yesterday_step,
        ));
    }

    public function actionReportHeartrate()
    {
        $space = $this->getSpace();
        $user = $this->getCare();

        $dataDevices = Device::find()->where(['user_id' => $user->id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->all();
        if (!$dataDevices){
            return $this->render('report-none', array(
                'space' => $space,
                'user' => $user,
            ));
        }

        $today = date("Y-m-d");
        date_default_timezone_set("GMT");
        $unixtoday = strtotime($today);
        $unixlastweek = strtotime('-1 week', $unixtoday);
        $start = $unixlastweek*1000;
        $end = $unixtoday*1000;

        $basicData = array_fill(0, 168, array_fill(0, 2, 0));

        $time = $start;
        for ($i = 0; $i < 168; $i++){
             $basicData[$i][0] = $time;
            $time = $time + 3600000;
        }

        $DATA = array();
        $devices = array(); //use to give device details
        $count = 0;
        foreach ($dataDevices as $dataDevice) {
            $deviceReportData = $basicData;
            $heartrate_data = Classlabelshourheart::find()->where(['hardware_id' => $dataDevice->hardware_id])
                ->andWhere(['>=', 'time', $start])->andWhere(['<', 'time', $end])->all();
            if ($heartrate_data){
                foreach ($heartrate_data as $rowData){
                    $hourlyheartrate = $rowData->heartrateLabel;
                    $hourlytime = substr($rowData->time, 0, 10); //division will have remainder

                    $intervaltime = $hourlytime - $unixlastweek;
                    $row = (int)($intervaltime/3600); //which hour

                    $deviceReportData[$row][1] = $hourlyheartrate;
                }

            }
            $DATA[$count] = $deviceReportData;
            $devices[$count] = $dataDevice;
            $count++;

            $lastData = Classlabelshourheart::find()->where(['hardware_id' => $dataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
            if (!is_null($lastData)){
                $lastData->seen = 1;
                $lastData->save();
            }

        }

//        Yii::getLogger()->log($basicData, Logger::LEVEL_INFO, 'MyLog');

        return $this->render('report-heartrate', array(
            'space' => $space,
            'user' => $user,
            'data' => $DATA,
            'devices' => $devices,
        ));
    }


    public function actionReportTest()
    {
        $space = $this->getSpace();
        $user = $this->getCare();

        return $this->render('report-test', array(
            'space' => $space,
            'user' => $user,
        ));
    }

    public function actionRemove()
    {
//        $this->forcePostRequest();
        $space = $this->getSpace();
        if (!$space->isAdmin())
            throw new HttpException(403, 'Access denied - Circle Administrator only!');

        $userGuid = Yii::$app->request->get('userGuid');
        $user = User::findOne(array('guid' => $userGuid));
        $space->setMember($user->id);


        // Redirect  back to Administration page
        return $this->htmlRedirect($space->createUrl('/space/manage/device', ['sguid' => $space->guid]));
    }
    public function getCare()
    {
        return User::findOne(['guid' => Yii::$app->request->get('rguid')]);
    }


    public function actionImage()
    {

    }
}
?>