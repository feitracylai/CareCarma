<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/26/2016
 * Time: 12:17 PM
 */

namespace humhub\modules\user\behaviors;

use humhub\modules\user\models\Device;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\User;
use humhub\modules\user\notifications\Linked;
use yii\base\Behavior;
use humhub\libs\GCM;
use yii\log\Logger;

class UserModelContact extends Behavior
{


    public function addContact(User $contactUser)
    {
        $user = $this->owner;
        /**************/
        $contact1 = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);
        if ($contact1 == null){
            $contact1 = new Contact();
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

        $gcm = new GCM();
        $device_id = $user->device_id;
        $device = Device::findOne(['device_id' => $device_id]);
        $data = array();
        $data['type'] = 'contact,updated';
        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $data);
        }


        /**************/
        $contact2 = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $user->id]);
        if ($contact2 == null){
            $contact2 = new Contact();
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

        $gcm = new GCM();
        $device_id = $contactUser->device_id;
        $device = Device::findOne(['device_id' => $device_id]);
        $data = array();
        $data['type'] = 'contact,updated';
        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $data);
        }
    }

    public function askAddContact(User $contactUser){
        $user = $this->owner;
        $contact1 = Contact::findOne(['user_id' => $user->id, 'contact_user_id' => $contactUser->id]);
        if ($contact1 == null){
            $contact1 = new Contact();
            $contact1->user_id = $user->id;
            $contact1->contact_user_id = $contactUser->id;
        }
        $contact1->linked = 0;
        $contact1->save();

        /**************/
        $contact2 = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $user->id]);
        if ($contact2 == null){
            $contact2 = new Contact();
            $contact2->user_id = $contactUser->id;
            $contact2->contact_user_id = $user->id;
        }
        $contact2->linked = 0;
        $contact2->save();

        //send notification
        $notification = new Linked();
        $notification->source = $contact1;
        $notification->originator = $user;
        $notification->send($contactUser);

    }



}