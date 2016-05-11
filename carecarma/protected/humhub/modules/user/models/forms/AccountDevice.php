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
            array(['currentPassword', 'deviceId'], 'required'),
            array('currentPassword', \humhub\modules\user\components\CheckPasswordValidator::className()),
            array('deviceId', 'unique', 'targetAttribute' => 'device_id', 'targetClass' => \humhub\modules\user\models\User::className(), 'message' => '{attribute} "{value}" is already in use!'),

        );
    }

    public function attributeLabels()
    {
        return array(
            'currentPassword' => Yii::t('UserModule.forms_AccountDeviceForm', 'Current password'),
            'deviceId' => Yii::t('UserModule.forms_AccountDeviceForm', 'New Device ID'),
        );
    }


}