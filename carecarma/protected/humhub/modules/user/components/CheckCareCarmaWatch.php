<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/6/2017
 * Time: 12:43 PM
 */

namespace humhub\modules\user\components;

use Yii;
use yii\validators\Validator;
use humhub\modules\user\models\Contact;

class CheckCareCarmaWatch extends Validator
{
    public function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        $userId = $object->user_id;

        $count = 0;
        $thisPrimary = false;
        foreach (Contact::find()->where(['user_id' => $userId])->each() as $contact) {
            if ($contact->carecarma_watch_number == 1){
                $count += 1;
                if ($contact->contact_id == $object->contact_id){
                    $thisPrimary = true;
                }
            }

        }
        if (!$thisPrimary){
            $count = $count + $value;
        }


        if ($count > 5 && $value == 1) {
            $object->addError($attribute, Yii::t('UserModule.components_CheckPrimaryPhone', "Your have more than 5 Primary Numbers on CoSMoS phone app now!"));
        }
    }
}