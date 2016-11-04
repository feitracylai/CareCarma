<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/26/2016
 * Time: 12:17 PM
 */

namespace humhub\modules\user\behaviors;


use humhub\modules\user\models\Contact;
use humhub\modules\user\models\User;
use yii\base\Behavior;

class UserModelContact extends Behavior
{


    public function addContact(User $contactUser)
    {
        $user = $this->owner;
        /**************/
        $contact1 = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);
        $data1 = 'update';
        if ($contact1 == null){
            $contact1 = new Contact();
            $data1 = 'add';
            $contact1->user_id = $user->id;
            $contact1->contact_user_id = $contactUser->id;
        }
        $contact1->linked = 1;
        $contact1->contact_first = $contactUser->profile->firstname;
        $contact1->contact_last = $contactUser->profile->lastname;
        $contact1->contact_email = $contactUser->email;
        $contact1->contact_mobile = $contactUser->profile->mobile;
        $contact1->work_phone = $contactUser->profile->phone_work;
        $contact1->home_phone = $contactUser->profile->phone_private;
        if ($contactUser->device_id != null){
            $contact1->device_phone = $contactUser->device->phone;
        }
        $contact1->save();
        $contact1->notifyDevice($data1);
        /**************/
        $contact2 = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $user->id]);
        $data2 = 'update';
        if ($contact2 == null){
            $contact2 = new Contact();
            $data2 = 'add';
            $contact2->user_id = $contactUser->id;
            $contact2->contact_user_id = $user->id;
        }
        $contact2->linked = 1;
        $contact2->contact_first = $user->profile->firstname;
        $contact2->contact_last = $user->profile->lastname;
        $contact2->contact_email = $user->email;
        $contact2->contact_mobile = $user->profile->mobile;
        $contact2->work_phone = $user->profile->phone_work;
        $contact2->home_phone = $user->profile->phone_private;
        if ($user->device_id != null){
            $contact2->device_phone = $user->device->phone;
        }
        $contact2->save();
        $contact2->notifyDevice($data2);

    }
}