<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\admin\controllers;

use humhub\modules\user\models\Device;
use Yii;
use yii\helpers\Url;
use humhub\compat\HForm;
use humhub\modules\admin\components\Controller;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Password;
use humhub\modules\user\models\Group;
use humhub\modules\user\models\Users;

/**
 * User management
 * 
 * @since 0.5
 */
class UserController extends Controller
{

    /**
     * Returns a List of Users
     */
    public function actionIndex()
    {
        $searchModel = new \humhub\modules\admin\models\UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', array(
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel
        ));
    }

    /**
     * Edits a user
     *
     * @return type
     */
    public function actionEdit()
    {
        $user = User::findOne(['id' => Yii::$app->request->get('id')]);

        if ($user == null)
            throw new \yii\web\HttpException(404, Yii::t('AdminModule.controllers_UserController', 'User not found!'));


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
                'device_id' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 45,
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
                'label' => Yii::t('AdminModule.controllers_UserController', 'Save'),
                'class' => 'btn btn-primary',
            ),
            'become' => array(
                'type' => 'submit',
                'label' => Yii::t('AdminModule.controllers_UserController', 'Become this user'),
                'class' => 'btn btn-danger',
            ),
            'delete' => array(
                'type' => 'submit',
                'label' => Yii::t('AdminModule.controllers_UserController', 'Delete'),
                'class' => 'btn btn-danger',
            ),
        );

        $form = new HForm($definition);
        $form->models['User'] = $user;
        $form->models['Profile'] = $profile;

        if ($form->submitted('save') && $form->validate()) {
            $device = Device::findOne(['device_id' => $form->models['User']->device_id]);
            if ($device != null || $form->models['User']->device_id == ''){
                if ($form->save()) {


                    //user in the contacts also change
                    $user->updateUserContacts();

                    //community database refresh
                    $community_users = Users::findOne(['id' => $user->id]);
                    $community_users->username = $user->username;
                    $community_users->profilename = $user->username;
                    $community_users->email = $user->email;
                    $community_users->firstname = $user->profile->firstname;
                    $community_users->lastname = $user->profile->lastname;
                    $community_users->mobile = $user->profile->mobile;
                    $community_users->address = $user->profile->street;
                    $community_users->unitnumber = $user->profile->address2;
                    $community_users->city = $user->profile->city;
                    $community_users->state = $user->profile->state;
                    $community_users->country = $user->profile->country;
                    $community_users->postalcode = $user->profile->zip;
                    $community_users->dob = $user->profile->birthday;
                    $community_users->gender = $user->profile->gender;
                    $community_users->save();
                    return $this->redirect(Url::toRoute('/admin/user'));
                }
            } else {
                $form->models['User']->addError('device_id', 'Invalid input! Please make sure that you entered the correct device ID.');
            }

        }

        // This feature is used primary for testing, maybe remove this in future
        if ($form->submitted('become')) {

            Yii::$app->user->switchIdentity($form->models['User']);
            return $this->redirect(Url::toRoute("/"));
        }

        if ($form->submitted('delete')) {
            return $this->redirect(Url::toRoute(['/admin/user/delete', 'id' => $user->id]));
        }

        return $this->render('edit', array('hForm' => $form));
    }

    public function actionAdd()
    {
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
                'device_id' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 45,
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
            $device = Device::findOne(['device_id' => $form->models['User']->device_id]);
            if ($device != null || $form->models['User']->device_id == ''){
                if ($form->models['User']->save()) {
                    // Save User Profile
                    $form->models['Profile']->user_id = $form->models['User']->id;
                    $form->models['Profile']->privacy = '0';
                    $form->models['Profile']->save();

                    // Save User Password
                    $form->models['UserPassword']->user_id = $form->models['User']->id;
                    $form->models['UserPassword']->setPassword($form->models['UserPassword']->newPassword);
                    $form->models['UserPassword']->save();

                    $users = new Users;
                    $users->firstname = $form->models['Profile']->firstname;
                    $users->lastname = $form->models['Profile']->lastname;
                    $users->username = $form->models['User']->username;
                    $users->profilename = $form->models['User']->username;
                    $users->email = $form->models['User']->email;

                    $users->id = $form->models['User']->id;
                    $users->usertype = 'user';
//                $users->activation_code = $input['activation_code'];
//                $users->createdyear = Date('Y');
//                $users->lattidude = $input['lat'];
//                $users->longitude = $input['lng'];
                    /* End Lat and Lon Calculation */
//                $users->createdon = Carbon::now();
                    $users->save();

                    return $this->redirect(Url::to(['index']));
                }
            } else {
                $form->models['User']->addError('device_id', 'Invalid input! Please make sure that you entered the correct device ID.');
            }

        }

        return $this->render('add', array('hForm' => $form));
    }

    /**
     * Deletes a user permanently
     */
    public function actionDelete()
    {

        $id = (int) Yii::$app->request->get('id');
        $doit = (int) Yii::$app->request->get('doit');


        $user = User::findOne(['id' => $id]);

        if ($user == null) {
            throw new HttpException(404, Yii::t('AdminModule.controllers_UserController', 'User not found!'));
        } elseif (Yii::$app->user->id == $id) {
            throw new HttpException(400, Yii::t('AdminModule.controllers_UserController', 'You cannot delete yourself!'));
        }

        if ($doit == 2) {

            $this->forcePostRequest();

            foreach (\humhub\modules\space\models\Membership::GetUserSpaces($user->id) as $space) {
                if ($space->isSpaceOwner($user->id)) {
                    $space->addMember(Yii::$app->user->id);
                    $space->setSpaceOwner(Yii::$app->user->id);
                }
            }
            $user->delete();
            return $this->redirect(Url::to(['/admin/user']));
        }

        return $this->render('delete', array('model' => $user));
    }

}
