<?php


namespace humhub\modules\space\modules\manage\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
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

        if ($user == null)
            throw new \yii\web\HttpException(404, Yii::t('SpaceModule.controllers_DeviceController', 'User not found!'));

        $user->scenario = 'editAdmin';
        $user->profile->scenario = 'editAdmin';
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
                'group_id' => array(
                    'type' => 'dropdownlist',
                    'class' => 'form-control',
                    'items' => \yii\helpers\ArrayHelper::map(Group::find()->all(), 'id', 'name'),
                ),
                'super_admin' => array(
                    'type' => 'checkbox',
                ),
                'auth_mode' => array(
                    'type' => 'dropdownlist',
                    'disabled' => 'disabled',
                    'class' => 'form-control',
                    'readonly' => 'true',
                    'items' => array(
                        User::AUTH_MODE_LOCAL => Yii::t('AdminModule.controllers_UserController', 'Local'),
                        User::AUTH_MODE_LDAP => Yii::t('AdminModule.controllers_UserController', 'LDAP'),
                    ),
                ),
                'status' => array(
                    'type' => 'dropdownlist',
                    'class' => 'form-control',
                    'items' => array(
                        User::STATUS_ENABLED => Yii::t('AdminModule.controllers_UserController', 'Enabled'),
                        User::STATUS_DISABLED => Yii::t('AdminModule.controllers_UserController', 'Disabled'),
                        User::STATUS_NEED_APPROVAL => Yii::t('AdminModule.controllers_UserController', 'Unapproved'),
                    ),
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
            if ($form->save()) {
                return $this->redirect(Url::toRoute(['/space/manage/device/index', 'sguid' => $space->guid]));
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
        $userModel->scenario = 'registration';

        $userPasswordModel = new Password();
        $userPasswordModel->scenario = 'registration';

        $profileModel = $userModel->profile;
        $profileModel->scenario = 'registration';

        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();

        $groupModels = \humhub\modules\user\models\Group::find()->orderBy('name ASC')->all();
        $defaultUserGroup = \humhub\models\Setting::Get('defaultUserGroup', 'authentication_internal');
        $groupFieldType = "dropdownlist";
        if ($defaultUserGroup != "") {
            $groupFieldType = "hidden";
        } else if (count($groupModels) == 1) {
            $groupFieldType = "hidden";
            $defaultUserGroup = $groupModels[0]->id;
        }
        if ($groupFieldType == 'hidden') {
            $userModel->group_id = $defaultUserGroup;
        }
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
                'group_id' => array(
                    'type' => $groupFieldType,
                    'class' => 'form-control',
                    'items' => \yii\helpers\ArrayHelper::map($groupModels, 'id', 'name'),
                    'value' => $defaultUserGroup,
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


                return $this->redirect($space->createUrl('/space/manage/device'));
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
