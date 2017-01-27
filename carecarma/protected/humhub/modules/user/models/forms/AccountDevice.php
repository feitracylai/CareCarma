<?php

namespace humhub\modules\user\models\forms;

use Yii;

class AccountDevice extends \yii\base\Model
{
    public $deviceId;
    public $currentPassword;


    public function rules()
    {
        return array(
            array(['deviceId', 'currentPassword'], 'required',  'on' => 'editDevice'),
            array('currentPassword', \humhub\modules\user\components\CheckPasswordValidator::className(), 'on' => 'userDevice'),

        );
    }

    public function attributeLabels()
    {
        return array(
            'currentPassword' => Yii::t('UserModule.forms_AccountDeviceForm', 'Current password'),
            'deviceId' => Yii::t('UserModule.forms_AccountDeviceForm', 'New Activation #'),
        );
    }


    public function scenarios()
    {

        $scenarios = parent::scenarios();
        $scenarios['editDevice'] = ['deviceId', 'currentPassword'];
        return $scenarios;
    }

}