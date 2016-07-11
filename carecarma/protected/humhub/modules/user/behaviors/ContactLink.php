<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/7/2016
 * Time: 11:59 AM
 */

namespace humhub\modules\user\behaviors;


use humhub\modules\user\notifications\LinkAccepted;
use humhub\modules\user\notifications\LinkDeclined;
use yii\base\Behavior;
use yii\log\Logger;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Contact;
use humhub\modules\user\notifications\Linked;


class ContactLink extends Behavior
{

    public function sendLink(User $contactUser, Contact $contact = null)
    {
        if ($contact == null){

            $contact = new Contact();
            $contact->scenario = 'linkContact';
            $contact->user_id = $this->owner->id;
        }
        $contact->contact_user_id = $contactUser->id;
        $contact->linked = 0;
        $contact->save();

        //send notification
        $notification = new Linked();
        $notification->source = $this->owner;
        $notification->originator = $this->owner;
        $notification->send($contactUser);

    }

    public function LinkUser(Contact $contact)
    {
        $contactUser = User::findOne(['id' => $contact->contact_user_id]);
        $contact->linked = 1;
        $contact->contact_first = $contactUser->profile->firstname;
        $contact->contact_last = $contactUser->profile->lastname;
        $contact->contact_mobile = $contactUser->profile->mobile;
        $contact->home_phone = $contactUser->profile->phone_private;
        $contact->work_phone = $contactUser->profile->phone_work;
        $contact->contact_email = $contactUser->email;
        if ($contactUser->device_id != null)
        {
            $contact->device_phone = $contactUser->device->phone;
        }
        $contact->save();
        $contact->notifyDevice('update');

        //Send notification to Accept
        $notification = new LinkAccepted();
        $notification->originator = $this->owner;
        $notification->source = $this->owner;
        $notification->send(User::findOne(['id' => $contact->user_id]));

        //Delete link notification for this user
        $notificationLink = new Linked();
        $notificationLink->source = User::findOne(['id' => $contact->user_id]);
        $notificationLink->delete($contactUser);

    }

    public function DeclineLink (Contact $contact)
    {
        $contactUser = User::findOne(['id' => $contact->contact_user_id]);

        if ($contact->contact_first == null && $contact->contact_last == null){
            $contact->delete();
        } else {
            $contact->linked = 0;
            $contact->save();
        }

        //Send notification to Decline
        $notification = new LinkDeclined();
        $notification->originator = $this->owner;
        $notification->source = $this->owner;
        $notification->send(User::findOne(['id' => $contact->user_id]));

        //Delete link notification for this user
        $notificationLink = new Linked();
        $notificationLink->source = User::findOne(['id' => $contact->user_id]);
        $notificationLink->delete($contactUser);

    }


}

