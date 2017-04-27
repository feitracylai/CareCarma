<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\massuserimport\models;

use humhub\modules\user\models\Invite;
use Yii;
use yii\helpers\Url;
use yii\base\Model;

/**
 * Container that holds all attributes to create a complete new user.
 * These are 'User', 'Password', 'Profile.'
 *
 * @property Profile
 * @see Password
 * @see User
 *
 * @property Profile $profile
 * @see Password $password
 * @see User $user
 *     
 * @package humhub.modules.massuserimport.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class UserImportContainer extends Model
{

    public $user;

    public $password;

    public $profile;

    public $autoConfirmPassword;

    public $ignorePasswordSave = false;

    public $ignoreUserSave = false;

    public $ignoreProfileSave = false;

    const EXCEPTION_ERROR_KEY = 'exception_error_occurred';

    const MAILING_ERROR_KEY = 'mailing_error_occurred';

    /**
     * Initialize container either with new instances or a given user.
     *
     * @see \yii\base\Object::init()
     */
    public function init($user = null, $autoConfirmPassword = true, $imported = 1)
    {
        if (empty($user)) {
            $this->autoConfirmPassword = $autoConfirmPassword;
            
            $this->user = new MassuserimportUser();
            $this->user->setScenario('massuserimport_create');
            
            $this->password = new MassuserimportPassword();
            $this->password->user_id = $this->user->id;
            $this->password->setScenario('massuserimport_create');
            
            $this->profile = new MassuserimportProfile();
            $this->profile->user_id = $this->user->id;
            $this->profile->setScenario('massuserimport_create');
            $this->user->populateRelation('profile', $this->profile);
            
            $this->user->imported = $imported;
            
            // set fixed values
            $groupModels = \humhub\modules\user\models\Group::find()->orderBy('name ASC')->all();
            $defaultUserGroupId = \humhub\models\Setting::Get('defaultUserGroup', 'authentication_internal');
            if (count($groupModels) == 1) {
                $defaultUserGroupId = $groupModels[0]->id;
            }
            $this->user->group_id = $defaultUserGroupId;
        } else {
            $this->user = $user;
            $this->user->setScenario('massuserimport_update');
            $this->profile = $this->user->profile;
            $this->profile->setScenario('massuserimport_update');
            $this->password = MassuserimportPassword::findOne([
                'user_id' => $this->user->id
            ]);
            $this->password->setScenario('massuserimport_update');
        }
        parent::init();
    }

    /**
     * Import the user by creating all necessary models.
     */
    public function save($sendMailAtSuccess = true)
    {
        if ($this->autoConfirmPassword) {
            $this->password->newPasswordConfirm = $this->password->newPassword;
        }
        $this->password->setPassword($this->password->newPassword);
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            if (! $this->ignoreUserSave && $this->user->validate()) {
                $this->user->save();
            }
            if (! empty($this->user->id) && ! $this->ignorePasswordSave && $this->password->validate()) {
                $this->password->user_id = $this->user->id;
                $this->password->save();
            }
            if (! empty($this->user->id) && ! $this->ignoreProfileSave && $this->profile->validate()) {
                $this->profile->user_id = $this->user->id;
                $this->profile->save();
            }
        } catch (Exception $e) {
            $this->addError(self::EXCEPTION_ERROR_KEY, $e->getMessage());
        }
        
        $this->addErrors(array_merge($this->user->errors, $this->password->errors, $this->profile->errors));
        
        if (! empty($this->errors)) {
            $transaction->rollBack();
        } elseif (! $this->sendImportMail()) {
            $this->addError(self::MAILING_ERROR_KEY, null);
            $transaction->rollBack();
        } else {
            $transaction->commit();
        }
    }

    public function attributes()
    {
        $userAttributes = $this->user->attributes();
        $passwordAttributes = $this->password->attributes();
        $profileAttributes = $this->profile->attributes();
        
        foreach ($userAttributes as $key => $value) {
            $userAttributes[$key] = "user.$value";
        }
        foreach ($passwordAttributes as $key => $value) {
            $passwordAttributes[$key] = "password.$value";
        }
        foreach ($profileAttributes as $key => $value) {
            $profileAttributes[$key] = "profile.$value";
        }
        
        return array_merge($userAttributes, $passwordAttributes, $profileAttributes);
    }

    public function safeAttributes()
    {
        $userAttributes = $this->user->safeAttributes();
        $passwordAttributes = $this->password->safeAttributes();
        $profileAttributes = $this->profile->safeAttributes();
        
        foreach ($userAttributes as $key => $value) {
            $userAttributes[$key] = "user.$value";
        }
        foreach ($passwordAttributes as $key => $value) {
            $passwordAttributes[$key] = "password.$value";
        }
        foreach ($profileAttributes as $key => $value) {
            $profileAttributes[$key] = "profile.$value";
        }
        
        return array_merge($userAttributes, $passwordAttributes, $profileAttributes);
    }

    public function sendImportMail()
    {
        $mail = Yii::$app->mailer->compose([
            'html' => '@humhub/modules/massuserimport/views/mails/ImportUser'
        ], [
            'model' => $this
        ]);
        $mail->setFrom([
            \humhub\models\Setting::Get('systemEmailAddress', 'mailing') => \humhub\models\Setting::Get('systemEmailName', 'mailing')
        ]);
        $mail->setTo($this->user->email);
        $mail->setSubject(Yii::t('MassuserimportModule.base', 'Welcome to %appName%', array(
            '%appName%' => Yii::$app->name
        )));
        
        return $mail->send();
    }

    public function generatePassword($override = false)
    {
        if (empty($this->password->newPassword) || $override) {
            $this->password->newPassword = base64_encode(openssl_random_pseudo_bytes(12));
            $this->password->newPasswordConfirm = $this->password->newPassword;
        }
    }

    public function generateUsername()
    {
        $username = '';
        $number = 1;
        if (! empty($this->user->username)) {
            $username = $this->user->username;
            // search a unique name on base of the given username
            while (MassuserimportUser::findOne([
                'username' => $username
            ]) !== null) {
                $username = $this->user->username . ++ $number;
            }
        } else 
            if (! empty($this->profile->firstname) && ! empty($this->profile->lastname)) {
                $affixCounter = 1;
                $username = substr($this->profile->firstname, 0, 1) . $this->profile->lastname;
                // search a unique name on base of firstname and lastname
                while (MassuserimportUser::findOne([
                    'username' => $username
                ]) !== null) {
                    if ($affixCounter >= strlen($this->profile->firstname)) {
                        $number ++;
                    } else {
                        $affixCounter ++;
                    }
                    $username = substr($this->profile->firstname, 0, $affixCounter) . $this->profile->lastname . ($number == 1 ? '' : $number);
                }
            }
        $this->user->username = $username;
    }

    /**
     * Check if a user with the given, unique attributes already exists.
     *
     * @return false if the unique attributes were invalid, else true.
     */
    public function validateUniqueAttr()
    {
        $record = MassuserimportUser::findOne([
            'email' => $this->user->email
        ]);
        if ($record != null) {
            false;
        }
        return true;
    }
}