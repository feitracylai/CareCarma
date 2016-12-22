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
            array('deviceId', 'required'),
            array('currentPassword', 'required'),
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


}