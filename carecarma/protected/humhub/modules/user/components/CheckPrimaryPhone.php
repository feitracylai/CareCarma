<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\components;

use Yii;
use yii\log\Logger;
use yii\validators\Validator;
use humhub\modules\user\models\Contact;

/**
 * CheckPrimaryWatch checks number of currently primary watch in user.
 *
 * @author luke
 */
class CheckPrimaryPhone extends Validator
{

    public function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        $user = Yii::$app->user->getIdentity();
        $count = 0;
        $thisPrimary = false;
        foreach (Contact::find()->where(['user_id' => $user->id])->each() as $contact) {
            if ($contact->phone_primary_number == 1){
                $count += 1;
                if ($contact->contact_id == $object->contact_id){
                    $thisPrimary = true;
                }
            }

        }
        if (!$thisPrimary){
            $count = $count + $value;
        }
        if ($count > 7 && $value == 1) {
            $object->addError($attribute, Yii::t('UserModule.components_CheckPrimaryPhone', "Your have more than 7 Primary Numbers on CoSMoS phone app now!"));
        }
    }

}
