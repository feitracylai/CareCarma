<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\massuserimport\controllers;

use Yii;
use humhub\modules\massuserimport\models\ExtendedUserInvite;
use humhub\modules\massuserimport\models\Csv;
use yii\web\UploadedFile;
use yii\web\Response;
use humhub\modules\massuserimport\models\ExtendedUserInviteSearch;
use yii\helpers\Url;
use humhub\modules\massuserimport\components\CsvParser;
use humhub\modules\massuserimport\models\User;
use humhub\modules\massuserimport\components\ErrorGenerator;
use humhub\modules\massuserimport\models\UserImportContainer;
use humhub\modules\user\models\forms\AccountRecoverPassword;
use humhub\modules\admin\models\UserImportSearch;
use humhub\modules\massuserimport\models\MassuserimportPassword;
use humhub\modules\user\models\Profile;
use yii\web\HttpException;
use Zend\Validator\InArray;
use humhub\models\Setting;
use humhub\modules\massuserimport\models\MassuserimportUser;
use humhub\modules\massuserimport\models\MassuserimportProfile;
use humhub\components\behaviors\AccessControl;

/**
 * RestController offers a rest json API to create delete and update users and their profiles.
 *
 * @package humhub.modules.massuserimport.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class RestController extends \humhub\modules\admin\components\Controller
{

    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        // Avoid required authentifcation
        if ($this->checkAccess()) {
            return [];
        }

        // Use standard acl
        return parent::behaviors();
    }

    /**
     * (non-PHPdoc)
     *
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!Setting::Get('activateJsonRestApi', 'massuserimport')) {
                throw new HttpException(404, Yii::t('MassuserimportModule.base', 'JSON REST API is not activated!'));
            }
            Yii::$app->request->parsers = [
                '*' => 'yii\web\JsonParser'
            ];
            return true;
        }
        return false;
    }

    /**
     * Render the markdown API documentation.
     * 
     * @throws HttpException MassuserimportApiDocumentation.md could not be found.
     * @return string the rendered view.
     */
    public function actionApiDocumentation()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;

        $path = Yii::$app->getModule('massuserimport')->getBasePath() . '/resources';

        $file = $path . '/MassuserimportApiDocumentation.md';

        if (file_exists($file)) {
            return $this->render('doc', array(
                        'markdown' => file_get_contents($file)
            ));
        } else {
            throw new HttpException(404, Yii::t('MassuserimportModule.base', 'Documentation could not be found!'));
        }
    }

    /**
     * Render a list of available rest actions and options.
     *
     * @return json: a list of all users.
     */
    public function actionIndex()
    {
        $safeAttributes = [];
        $safeAttributes['profile'] = [];
        $safeAttributes['user'] = [];
        $safeAttributes['password'] = [];

        $dummy = new MassuserimportUser();
        $dummy->setScenario('massuserimport_create');
        $safeAttributes['user']['massuserimport_create'] = $dummy->safeAttributes();
        $dummy->setScenario('massuserimport_update');
        $safeAttributes['user']['massuserimport_update'] = $dummy->safeAttributes();

        $dummy = new MassuserimportProfile();
        $dummy->setScenario('massuserimport_create');
        $safeAttributes['profile']['massuserimport_create'] = $dummy->safeAttributes();
        $dummy->setScenario('massuserimport_update');
        $safeAttributes['profile']['massuserimport_update'] = $dummy->safeAttributes();

        $dummy = new MassuserimportPassword();
        $dummy->setScenario('massuserimport_create');
        $safeAttributes['password']['massuserimport_create'] = $dummy->safeAttributes();
        $dummy->setScenario('massuserimport_update');
        $safeAttributes['password']['massuserimport_update'] = $dummy->safeAttributes();

        return [
            'list' => [
                'required_fields' => 'none',
                'response' => 'array of all user data'
            ],
            'view' => [
                'required_fields' => [
                    'email',
                    'id',
                    'guid',
                    'username'
                ],
                'information' => 'Only one of the required fields is needed.'
            ],
            'create' => [
                'required_fields' => [
                    'user' => [
                        'email'
                    ],
                    'profile' => [
                        'firstname',
                        'lastname'
                    ],
                    'apipassword' => ""
                ],
                'all_editable_fields' => [
                    'user' => $safeAttributes['user']['massuserimport_create'],
                    'profile' => $safeAttributes['profile']['massuserimport_create'],
                    'password' => $safeAttributes['password']['massuserimport_create']
                ],
                'information' => 'Please note: If you provide no password a safe one will be generated. If you provide no username, it will be generated from the firstname and lastname. If the given username is not unique, it will be slightly changed to a unique one. The only required parameters are user.email | profile.firstname | profile.lastname. The created user will be informed via email about his new account.'
            ],
            'update' => [
                'required_fields' => [
                    'user' => [
                        'id',
                        'guid'
                    ],
                    'apipassword' => ""
                ],
                'all_editable_fields' => [
                    'user' => $safeAttributes['user']['massuserimport_update'],
                    'profile' => $safeAttributes['profile']['massuserimport_update'],
                    'password' => $safeAttributes['password']['massuserimport_update']
                ],
                'information' => 'Please note: Only one of the user identifiers (guid, id) is needed. You can only change the password if you provide the old one.'
            ],
            'delete' => [
                'required_fields' => [
                    'user' => [
                        'id',
                        'guid'
                    ],
                    'apipassword' => ""
                ],
                'information' => 'Please note: Only one of the user identifiers (guid, id) is needed. You can only change the password if you provide the old one.'
            ]
        ];
    }

    /**
     * Render a list of all users.
     *
     * @return json: a list of all users.
     */
    public function actionList()
    {
        $users = MassuserimportUser::find()->all();
        $entries = [];
        foreach ($users as $user) {
            $entries['users'][] = [
                'user' => $user,
                'profile' => $user->profile
            ];
        }
        return $entries;
    }

    /**
     * Accepts a json with a id|email|username|guid and renders the user data as json if the id is valid.
     *
     * @return json: a list of all users.
     */
    public function actionView()
    {
        $user = $this->loadUserFromSimpleParams();
        return [
            'user' => $user,
            'profile' => empty($user) ? null : $user->profile
        ];
    }

    /**
     * Accepts a json with combined user / profile / password data and creates a user if the data is valid.
     *
     *
     * @return json: status of the operation.
     */
    public function actionCreate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $container = new UserImportContainer();
        $container->init(null, false);
        if (is_array($params)) {
            if (array_key_exists('user', $params)) {
                $container->user->load($params['user'], '');
            }
            if (array_key_exists('profile', $params)) {
                $container->profile->load($params['profile'], '');
            }
            // a password must be provided or generated if an user user created
            if (array_key_exists('password', $params)) {
                if (array_key_exists('newPassword', $params['password'])) {
                    $container->password->load($params['password'], '');
                } else {
                    $container->generatePassword();
                }
            } else {
                $container->generatePassword();
            }
            // generate username if it was empty or not unique
            $container->generateUsername();
        }
        $container->save();
        if (empty($container->errors)) {
            return [
                'success' => true,
                'message' => Yii::t('MassuserimportModule.base', 'The user was successfully created with id %id%.', [
                    '%id%' => $container->user->id
                ])
            ];
        } else {
            return [
                'name' => 'Errors occurred.',
                'message' => 'Errors occurred. The user could not be created.',
                'details' => $container->errors,
                'status' => 400,
                'code' => 0
            ];
        }
    }

    /**
     * Accepts a json with combined user / profile / password data and updates a user if the data is valid.
     *
     * @return json: status of the operation.
     */
    public function actionUpdate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $container = new UserImportContainer();
        $container->init($this->loadUserFromJson(), false, 0);

        $container->user->load($params['user'], '');
        if (array_key_exists('profile', $params)) {
            $container->profile->load($params['profile'], '');
        }
        if (array_key_exists('password', $params)) {
            if (array_key_exists('newPassword', $params['password'])) {
                $container->password->load($params['password'], '');
            }
        } else {
            $container->ignorePasswordSave = true;
        }

        $container->save();
        if (empty($container->errors)) {
            return [
                'success' => true,
                'message' => Yii::t('MassuserimportModule.base', 'The user was successfully updated.')
            ];
        } else {
            return [
                'name' => 'Errors occurred.',
                'message' => 'Errors occurred. The user could not be updated.',
                'details' => $container->errors,
                'status' => 400,
                'code' => 0
            ];
        }
    }

    /**
     * Accepts a json with a user id and deletes the user if the id is valid.
     *
     * @return json: status of the operation.
     */
    public function actionDelete()
    {
        $user = $this->loadUserFromJson();
        if (empty($user)) {
            throw new HttpException(404, Yii::t('MassuserimportModule.base', 'User not found!'));
        }
        $newSpaceOwnerEmail = Yii::$app->request->post('newspaceowneremail');
        if ($newSpaceOwnerEmail == $user->email) {
            throw new HttpException(400, Yii::t('MassuserimportModule.base', 'You must provide the email adress of a valid new space owner!'));
        }
        $newSpaceOwner = \humhub\modules\user\models\User::findOne([
                    'email' => $newSpaceOwnerEmail
        ]);
        if (empty($newSpaceOwner)) {
            throw new HttpException(400, Yii::t('MassuserimportModule.base', 'You must provide the email adress of a valid new space owner!'));
        }
        foreach (\humhub\modules\space\models\Membership::GetUserSpaces($user->id) as $space) {
            if ($space->isSpaceOwner($user->id)) {
                $space->addMember($newSpaceOwner->id);
                $space->setSpaceOwner($newSpaceOwner->id);
            }
        }
        $user->delete();

        return [
            'success' => true,
            'message' => Yii::t('MassuserimportModule.base', 'The user was successfully deleted. The user with email %newspaceowner% overtakes the ownership of the user\'s spaces.', [
                '%newspaceowner%' => $newSpaceOwnerEmail
            ])
        ];
    }

    /**
     * Check if the incoming request has access to the database.
     */
    public function checkAccess()
    {
        $givenPassword = Yii::$app->request->get('apipassword') === null ? Yii::$app->request->post('apipassword') : Yii::$app->request->get('apipassword');
        return $givenPassword === Setting::Get('jsonRestApiPassword', 'massuserimport');
    }

    /**
     * Loads a user from given get or post parameters.
     *
     * @throws HttpException if the user wasnt found.
     * @return the user
     */
    private function loadUserFromSimpleParams()
    {
        // check if the param is defined as get or post param. Post param overwrites get param.
        $id = Yii::$app->request->get('id') === null ? Yii::$app->request->post('id') : Yii::$app->request->get('id');
        $guid = !empty($id) ? null : Yii::$app->request->get('guid') === null ? Yii::$app->request->post('guid') : Yii::$app->request->get('guid');
        $email = !empty($guid) ? null : Yii::$app->request->get('email') === null ? Yii::$app->request->post('email') : Yii::$app->request->get('email');
        $username = !empty($email) ? null : Yii::$app->request->get('username') === null ? Yii::$app->request->post('username') : Yii::$app->request->get('username');

        $user = MassuserimportUser::find()->where([
                    'email' => $email
                ])
                ->orWhere([
                    'id' => $id
                ])
                ->orWhere([
                    'guid' => $guid
                ])
                ->orWhere([
                    'username' => $username
                ])
                ->one();
        if (empty($user)) {
            throw new HttpException(404, Yii::t('MassuserimportModule.base', 'User not found!'));
        }
        return $user;
    }

    /**
     * Loads a user from a given user json.
     *
     * @throws HttpException if the user wasnt found.
     * @return the user
     */
    private function loadUserFromJson()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $user = null;
        $guid = null;
        $id = null;
        if (is_array($params)) {
            if (array_key_exists('user', $params)) {
                if (array_key_exists('id', $params['user']) && !empty($params['user']['id'])) {
                    $id = $params['user']['id'];
                }
                if (array_key_exists('guid', $params['user']) && !empty($params['user']['guid'])) {
                    $guid = $params['user']['guid'];
                }
            }
            $user = MassuserimportUser::find()->where([
                        'id' => $id
                    ])
                    ->orWhere([
                        'guid' => $guid
                    ])
                    ->one();
            if (!empty($user)) {
                return $user;
            }
        }
        throw new HttpException(404, Yii::t('MassuserimportModule.base', 'User not found!'));
    }

}
