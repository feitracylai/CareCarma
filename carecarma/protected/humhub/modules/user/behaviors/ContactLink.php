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

        //send notification
        $notification = new Linked();
        $notification->source = $this->owner;
        $notification->originator = $user;
        $notification->send($contactUser);

    }

    public function LinkUser($contactUser, $user)
    {
        if ($this->owner->contact_first == null & $this->owner->contact_last == null)
        {
            $this->owner->notifyDevice('add');
        } else {
            $this->owner->notifyDevice('update');
        }

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

        if ($this->owner->contact_first == null && $this->owner->contact_last == null){
            $this->owner->delete();
        } else {
            $this->owner->linked = 0;
            $this->owner->save();
        }

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
            $this->owner->linked = 0;
            $this->owner->contact_user_id = null;
            $this->owner->save();
            $this->owner->notifyDevice('update');

            //Send notification to Remove
            $notification = new LinkRemove();
            $notification->originator = $user;
            $notification->source = $this->owner;
            $notification->send(User::findOne(['id' => $this->owner->user_id]));

        } else {
            if ($this->owner->contact_first == null && $this->owner->contact_last == null){
                $this->owner->delete();
            } else {
                $this->owner->linked = 0;
                $this->owner->save();
            }
            //Delete link notification for this user
            $notificationLink = new Linked();
            $notificationLink->source = $this->owner;
            $notificationLink->delete(User::findOne(['id' => $this->owner->user_id]));
        }



    }


}

