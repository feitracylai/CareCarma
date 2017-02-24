<?php

namespace humhub\modules\user\models\forms;

use humhub\modules\user\components\CheckActivateValidator;
use Yii;

class AccountDevice extends \yii\base\Model
{
    public $deviceId;
    public $currentPassword;


    public function rules()
    {
        return array(
            array(['deviceId', 'currentPassword'], 'required',  'on' => 'editDevice'),
//            array('currentPassword', \humhub\modules\user\components\CheckPasswordValidator::className()),

            array(['deviceId'], 'string', 'max' => 4),
            array('deviceId', CheckActivateValidator::className()),

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