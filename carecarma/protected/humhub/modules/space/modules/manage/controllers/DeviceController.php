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
 * Member Controller
 *
 * @author Luke
 */
class DeviceController extends Controller
{

    /**
     * CareReceiver Administration Action
     */
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


//    public function actionEdit()
//    {
//        $space = $this->getSpace();
//        $user = User::findOne(['id' => Yii::$app->request->get('id')]);
//        $deviceOld = Device::findOne(['device_id' => $user->device_id]);
//
//
//        if ($user == null)
//            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_DeviceController', 'User not found!'));
//
//        $user->scenario = 'editCare';
//
//
//        // Build Form Definition
//        $definition = array();
//        $definition['elements'] = array();
//        // Add User Form
//        $definition['elements']['User'] = array(
//            'type' => 'form',
//            'elements' => array(
//                'email' => array(
//                    'type' => 'text',
//                    'class' => 'form-control',
//                    'maxlength' => 100,
//                ),
//                'device_id'=> array(
//                    'type' => 'text',
//                    'class' => 'form-control',
//                    'maxlength' => 45,
//                ),
//            ),
//        );
//
//
//
//        // Get Form Definition
//        $definition['buttons'] = array(
//            'save' => array(
//                'type' => 'submit',
//                'label' => Yii::t('SpaceModule.controllers_DeviceController', 'Save'),
//                'class' => 'btn btn-primary',
//            ),
//            'delete' => array(
//                'type' => 'delete',
//                'label' => Yii::t('SpaceModule.controllers_DeviceController', 'Delete'),
//                'class' => 'btn btn-danger',
//                'data-confirm' => 'Are you sure? This person will become a general member in this space.'
//            ),
//        );
//
//        $form = new HForm($definition);
//        $form->models['User'] = $user;
//
//        if ($form->submitted('save') && $form->validate()) {
//            $device = Device::findOne(['device_id' => $form->models['User']->device_id]);
//
//            if ($device != null) {
//
//                if ($form->save()) {
//
//                    if ($device != $deviceOld){
//
//
//                        if($device->gcmId != null) {
//
//                            $gcm = new GCM();
//                            $push = new Push();
//
//                            $push->setTitle('user id');
//                            $push->setData($form->models['User']->id);
//
//
//                            $gcm_registration_id = $device->gcmId;
//
//                            $gcm->send($gcm_registration_id, $push->getPush());
//
//                        }
//                        if ($deviceOld->gcmId != null) {
//                            $gcmOld = new GCM();
//                            $pushOld = new Push();
//                            $pushOld->setTitle('user');
//                            $pushOld->setData('delete device');
//                            $gcmOld->send($deviceOld->gcmId, $pushOld->getPush());
//
//
//                        }
//
//                        $deviceOld->user_id = null;
//                        $deviceOld->save();
//                    }
//
//
//
//                    $user->gcmId = $device->gcmId;
//                    $user->save();
//
//                    $device->user_id = $form->models['User']->id;
//                    $device->save();
//
//                    return $this->redirect(Url::toRoute(['/space/manage/device/index', 'sguid' => $space->guid]));
//                }
//            }
//            else {
//                $form->models['User']->addError('device_id', 'Invalid input! Please make sure that you entered the correct device ID.');
//            }
//
//        }
//
//        // This feature is used primary for testing, maybe remove this in future
//
//        if ($form->submitted('delete')) {
////            return $this->redirect(Url::toRoute(['/space/manage/device/delete', 'id' => $user->id]));
//            return $this->redirect($space->createUrl('/space/manage/device/remove', ['userGuid' => $user->guid, 'sguid' => $space->guid]));
//        }
//
//        return $this->render('edit', array(
//            'hForm' => $form,
//            'space' => $space,
//            'user' => $user,
//        ));
//    }


    public function actionAdd()
    {
        $space = $this->getSpace();
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
            'title' => Yii::t('UserModule.controllers_AuthController', 'Account'),
            'elements' => array(
                'username' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 25,
                ),
                'email' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 100,
                ),
                'device_id'=> array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 45,
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
                'label' => Yii::t('UserModule.controllers_AuthController', 'Create account'),
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
            if ($device != null) {
                if ($form->models['User']->save()) {
                    // Save User Profile
                    $form->models['Profile']->user_id = $form->models['User']->id;
                    $form->models['Profile']->save();

                    // Save User Password
                    $form->models['UserPassword']->user_id = $form->models['User']->id;
                    $form->models['UserPassword']->setPassword($form->models['UserPassword']->newPassword);
                    $form->models['UserPassword']->save();

                    // Become Care Receiver in this space
                    $space->addMember($form->models['User']->id);
                    $space->setCareReceiver($form->models['User']->id);

                    $device->user_id = $form->models['User']->id;
                    $device->save();

                    if($device->gcmId != null) {

                        $gcm = new GCM();
                        $push = new Push();

                        $push->setTitle('user id');
                        $push->setData($form->models['User']->id);


                        $gcm_registration_id = $device->gcmId;

                        $gcm->send($gcm_registration_id, $push->getPush());
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

    public function actionEdit() {
        $space = $this->getSpace();
        $user =  User::findOne(['id' => Yii::$app->request->get('id')]);


        $emailModel = new \humhub\modules\user\models\forms\AccountChangeEmail;





        if ($emailModel->load(Yii::$app->request->post()) && $emailModel->validate() && $emailModel->sendChangeEmail()) {
            Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_AccountController', 'Saved'));
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
        $user =  User::findOne(['id' => Yii::$app->request->get('id')]);


        $deviceOld = Device::findOne(['device_id' => $user->device_id]);
        $deviceModel = new \humhub\modules\user\models\forms\AccountDevice();


        if ($deviceModel->load(Yii::$app->request->post())&& $deviceModel->validate()) {

            $device = Device::find()->where(['device_id' => $deviceModel->deviceId])->one();
            if ($device!=null) {
                if ($device != $deviceOld) {
                    $deviceOld->user_id = null;
                    $deviceOld->save();

                    $user->device_id = $deviceModel->deviceId;
                    $device->user_id = $user->id;
                    $device->save();

                    if ($device->gcmId != null ) {
                        $user->gcmId = $device->gcmId;

                        $gcm = new GCM();
                        $push = new Push();

                        $push->setTitle('user id');
                        $push->setData($user->getId());


                        $gcm_registration_id = $user->gcmId;

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

                }


                Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_AccountController', 'Saved'));

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
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);

        // Get Form Definition
        $user->profile->scenario = 'editCare';
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

            Yii::$app->getSession()->setFlash('data-saved', Yii::t('UserModule.controllers_AccountController', 'Saved'));
            return $this->redirect(Url::to(['edit']));
        }

        return $this->render('profile', array(
            'hForm' => $form,
            'space' => $space,
            'user' => $user,
        ));
    }

    public function actionContact() {
        $space = $this->getSpace();
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);

        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user->id);

        return $this->render('contact', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'space' => $space,
            'user' => $user,
        ]);
    }

    public function actionSettings() {
        $space = $this->getSpace();
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);

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

        return $this->render('settings', array('space' => $space, 'model' => $model, 'languages' => Yii::$app->params['availableLanguages']));
    }


    public function actionDelete()
    {
        $isSpaceOwner = false;
        $space = $this->getSpace();
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);

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
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);

        return $this->render('report', array(
            'space' => $space,
            'user' => $user
        ));
    }

    public function actionRemove()
    {
//        $this->forcePostRequest();

        $space = $this->getSpace();
        $userGuid = Yii::$app->request->get('userGuid');
        $user = User::findOne(array('guid' => $userGuid));

        $space->setMember($user->id);
        // Redirect  back to Administration page
        return $this->htmlRedirect($space->createUrl('/space/manage/device/index'));
    }



}

?>
