<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/7/2016
 * Time: 11:59 AM
 */

namespace humhub\modules\user\behaviors;


use humhub\modules\user\notifications\LinkAccepted;
use humhub\modules\user\notifications\LinkDenied;
use humhub\modules\user\notifications\LinkRemove;
use yii\base\Behavior;
use yii\log\Logger;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Contact;
use humhub\modules\user\notifications\Linked;


class ContactLink extends Behavior
{

    public function sendLink(User $contactUser, User $user)
    {

        if ($this->owner->user_id == null){

            $this->owner->scenario = 'linkContact';
            $this->owner->user_id = $user->id;
        }
        $this->owner->contact_user_id = $contactUser->id;
        $this->owner->linked = 0;

//        \Yii::getLogger()->log($this->owner, Logger::LEVEL_INFO, 'MyLog');
        $this->owner->save();

        $oppContact = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $user->id]);
        if ($oppContact == null){
            $oppContact = new Contact();
            $oppContact->scenario = 'linkContact';
            $oppContact->user_id = $contactUser->id;
            $oppContact->contact_user_id = $user->id;
        }
        $oppContact->linked = 0;
        $oppContact->save();


        //send notification
        $notification = new Linked();
        $notification->source = $this->owner;
        $notification->originator = $user;
        $notification->send($contactUser);

    }

    public function LinkUser($contactUser, $user)
    {

        $this->owner->linked = 1;
        $this->owner->contact_first = $contactUser->profile->firstname;
        $this->owner->contact_last = $contactUser->profile->lastname;
        $this->owner->contact_mobile = $contactUser->profile->mobile;
        $this->owner->home_phone = $contactUser->profile->phone_private;
        $this->owner->work_phone = $contactUser->profile->phone_work;
        $this->owner->contact_email = $contactUser->email;
        if ($contactUser->device_id != null)
        {
            $this->owner->device_phone = $contactUser->device->phone;
        }
        $this->owner->save();
//        $this->owner->notifyDevice($data);

        //add contact 2
        $contact = Contact::findOne(['user_id' => $contactUser->id, 'contact_user_id' => $user->id]);
        if ($contact == null){
            $contact = new Contact();
            $contact->user_id = $contactUser->id;
            $contact->contact_user_id = $user->id;
        }
        $contact->linked = 1;
        $contact->contact_first = $user->profile->firstname;
        $contact->contact_last = $user->profile->lastname;
        $contact->contact_mobile = $user->profile->mobile;
        $contact->home_phone = $user->profile->phone_private;
        $contact->work_phone = $user->profile->phone_work;
        $contact->contact_email = $user->email;
        if ($user->device_id != null)
        {
            $contact->device_phone = $user->device->phone;
        }
        $contact->save();
//        $contact->notifyDevice('add');


        //Send notification to Accept
        $notification = new LinkAccepted();
        $notification->source = $this->owner;
        $notification->originator = $contactUser;
        $notification->send($user);

        //Delete link notification for this user
        $notificationLink = new Linked();
        $notificationLink->source = $this->owner;
        $notificationLink->delete($contactUser);

    }

    public function DenyLink ($contactUser, $user)
    {

//        if ($this->owner->contact_first == null && $this->owner->contact_last == null){
//            $this->owner->delete();
//        } else {
//            $this->owner->linked = 1;
//            $this->owner->contact_user_id = null;
//            $this->owner->save();
//        }
        $this->owner->delete();

        //Send notification to Deny
        $notification = new LinkDenied();
        $notification->source = $this->owner;
        $notification->originator = $contactUser;
        $notification->send($user);

        //Delete link notification for this user
        $notificationLink = new Linked();
        $notificationLink->source = $this->owner;
        $notificationLink->delete($contactUser);

    }

    public function CancelLink($user)
    {
//        \Yii::getLogger()->log(\Yii::$app->user->id, Logger::LEVEL_INFO, 'MyLog');

        if ($this->owner->contact_user_id == $user->id) {

            if ($this->owner->linked == 0){
                $this->DenyLink($user, User::findOne(['id' => $this->owner->user_id]));
            } else {
                $this->owner->linked = 0;
                $this->owner->contact_user_id = null;
                $this->owner->save();
                $this->owner->notifyDevice('update');

                //Send notification to Remove
                $notification = new LinkRemove();
                $notification->originator = $user;
                $notification->source = $this->owner;
                $notification->send(User::findOne(['id' => $this->owner->user_id]));
            }



        } else {
            if ($this->owner->contact_first == null && $this->owner->contact_last == null){
                $this->owner->delete();
            } else {
                $this->owner->linked = 1;
                $this->owner->contact_user_id = null;
                $this->owner->save();
            }
            //Delete link notification for this user
            $notificationLink = new Linked();
            $notificationLink->source = $this->owner;
            $notificationLink->originator = $user;
            $notificationLink->delete(User::findOne(['id' => $this->owner->contact_user_id]));
        }



    }



}

