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
use humhub\modules\user\models\User;

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
        foreach (Contact::find()->where(['user_id' => $user->id])->each() as $contact) {
            if ($contact->phone_primary_number == 1)
                $count += 1;
        }
        $count = $count + $value;
        if ($count > 7) {
            $object->addError($attribute, Yii::t('UserModule.components_CheckPrimaryPhone', "Your have more than 6 Primary Numbers on Cosmos phone app now!"));
        }
    }

}
