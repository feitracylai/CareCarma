<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\components;

use Yii;
use yii\validators\Validator;
use humhub\modules\user\models\Contact;

/**
 * CheckPrimaryWatch checks number of currently primary watch in user.
 *
 * @author luke
 */
class CheckPrimaryWatch extends Validator
{

    public function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;

        $userId = $object->user_id;
        $count = 0;
        $thisPrimary = false;
        foreach (Contact::find()->where(['user_id' => $userId])->each() as $contact) {
            if ($contact->watch_primary_number == 1){
                $count += 1;
                if ($contact->contact_id == $object->contact_id){
                    $thisPrimary = true;
                }
            }

        }

        if (!$thisPrimary){
            $count = $count + $value;
        }

        if ($count > 6 && $value == 1) {
            $object->addError($attribute, Yii::t('UserModule.components_CheckPrimaryWatch', "Your have more than 6 Primary Numbers on CoSMoS watch app now!"));
        }
    }

}
