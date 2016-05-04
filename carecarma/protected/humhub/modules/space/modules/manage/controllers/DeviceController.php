<?php


namespace humhub\modules\space\modules\manage\controllers;

use humhub\modules\user\models\Device;
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
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\space\modules\manage\components\Controller;
use humhub\modules\space\modules\manage\models\DeviceUserSearch;
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

    public function actionEdit()
    {
        $space = $this->getSpace();
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);
        $deviceOld = Device::findOne(['device_id' => $user->device_id]);

        if ($user == null)
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_DeviceController', 'User not found!'));

        $user->scenario = 'editCare';
        $user->profile->scenario = 'editCare';
        $profile = $user->profile;

        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();
        // Add User Form
        $definition['elements']['User'] = array(
            'type' => 'form',
            'title' => 'Account',
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


        // Add Profile Form
        $definition['elements']['Profile'] = array_merge(array('type' => 'form'), $profile->getFormDefinition());

        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'label' => Yii::t('SpaceModule.controllers_DeviceController', 'Save'),
                'class' => 'btn btn-primary',
            ),
            'delete' => array(
                'type' => 'delete',
                'label' => Yii::t('SpaceModule.controllers_DeviceController', 'Delete'),
                'class' => 'btn btn-danger',
                'data-confirm' => 'Are you sure? This person will become a general member in this space.'
            ),
        );

        $form = new HForm($definition);
        $form->models['User'] = $user;
        $form->models['Profile'] = $profile;

        if ($form->submitted('save') && $form->validate()) {
            $device = Device::findOne(['device_id' => $form->models['User']->device_id]);

            if ($device != null) {

                if ($form->save()) {

                    if ($device != $deviceOld){


                        if($device->gcmId != null) {

                            $gcm = new GCM();
                            $push = new Push();

                            $push->setTitle('user id');
                            $push->setData($form->models['User']->id);


                            $gcm_registration_id = $device->gcmId;

                            $gcm->send($gcm_registration_id, $push->getPush());

                        }
                        if ($deviceOld->gcmId != null) {
                            $gcmOld = new GCM();
                            $pushOld = new Push();
                            $pushOld->setTitle('user');
                            $pushOld->setData('delete device');
                            $gcmOld->send($deviceOld->gcmId, $pushOld->getPush());


                        }

                        $deviceOld->user_id = null;
                        $deviceOld->save();
                    }



                    $user->gcmId = $device->gcmId;
                    $user->save();

                    $device->user_id = $form->models['User']->id;
                    $device->save();

                    return $this->redirect(Url::toRoute(['/space/manage/device/index', 'sguid' => $space->guid]));
                }
            }
            else {
                $form->models['User']->addError('device_id', 'Invalid input! Please make sure that you entered the correct device ID.');
            }

        }

        // This feature is used primary for testing, maybe remove this in future

        if ($form->submitted('delete')) {
//            return $this->redirect(Url::toRoute(['/space/manage/device/delete', 'id' => $user->id]));
            return $this->redirect($space->createUrl('/space/manage/device/remove', ['userGuid' => $user->guid, 'sguid' => $space->guid]));
        }

        return $this->render('edit', array(
            'hForm' => $form,
            'space' => $space,
        ));
    }

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
