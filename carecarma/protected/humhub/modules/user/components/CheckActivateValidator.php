<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 2/8/2017
 * Time: 4:08 PM
 */

namespace humhub\modules\user\components;


use yii\validators\Validator;
use humhub\modules\user\models\Device;
use Yii;
use yii\log\Logger;

class CheckActivateValidator extends Validator
{
    public function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        Yii::getLogger()->log($value, Logger::LEVEL_INFO, 'MyLog');
        if (!$this->checkDevice($value)) {
            $object->addError($attribute, Yii::t('UserModule.components_CheckPasswordValidator', "Activation ID is incorrect!"));
        }
    }

    private function checkDevice($value)
    {
        $check = true;
        if ($value == ''){
            Yii::getLogger()->log('deviceId=nothing', Logger::LEVEL_INFO, 'MyLog');
            return true;
        }
        $device = Device::find()->where(['device_id' => $value])->one();

        if ($device == null || $device->activate == 1){
            Yii::getLogger()->log('device=null', Logger::LEVEL_INFO, 'MyLog');
            return false;
        }

        return $check;
    }
}