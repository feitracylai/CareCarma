<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/23/2016
 * Time: 4:28 PM
 */

namespace humhub\modules\space\behaviors;


use humhub\modules\space\models\Membership;
use humhub\modules\user\models\User;
use yii\base\Behavior;
use humhub\modules\space\notifications\AddCare;
use humhub\modules\space\notifications\CareAccpted;
use humhub\modules\space\notifications\CareDenied;
use yii\log\Logger;

class CareLink extends Behavior
{
    public function addCare($sender, $userId = "")
    {
        $membership = Membership::findOne(['space_id' => $this->owner->id, 'user_id' => $userId]);
        $membership->originator_user_id = $sender->id;
        $membership->add_care = 0;
        $membership->save();

        $user = User::findOne(['id' => $userId]);

        $notification = new AddCare();
        $notification->source = $this->owner;
        $notification->originator = $sender;
        $notification->send($user);
    }

    public function acceptCare($careUser)
    {
        $membership = Membership::findOne(['space_id' => $this->owner->id, 'user_id' => $careUser->id]);
        if ($membership != null)
        {
            $admin = User::findOne(['id' => $membership->originator_user_id]);
            $membership->group_id = 'device';
            $membership->add_care = 1;
            $membership->save();

            //Send notification to Accept
            $notification = new CareAccpted();
            $notification->source = $this->owner;
            $notification->originator = $careUser;
            $notification->send($admin);

            //Delete link notification for this user
            $notificationLink = new AddCare();
            $notificationLink->source = $this->owner;
            $notificationLink->delete($careUser);
        }

        return;

    }

    public function denyCare($careUser)
    {

        $membership = Membership::findOne(['space_id' => $this->owner->id, 'user_id' => $careUser->id]);

        if ($membership != null)
        {
            $admin = User::findOne(['id' => $membership->originator_user_id]);
            $membership->originator_user_id = null;
            $membership->add_care = null;
            $membership->save();

//            \Yii::getLogger()->log($admin, Logger::LEVEL_INFO, 'MyLog');

            //Send notification to Deny
            $notification = new CareDenied();
            $notification->source = $this->owner;
            $notification->originator = $careUser;
            $notification->send($admin);



            //Delete link notification for this user
            $notificationLink = new AddCare();
            $notificationLink->source = $this->owner;
            $notificationLink->delete($careUser);
        }

        return;

    }


    public function cancelAdd($careUser)
    {

        $membership = Membership::findOne(['space_id' => $this->owner->id, 'user_id' => $careUser->id]);

        if ($membership != null)
        {
            $membership->originator_user_id = null;
            $membership->add_care = null;
            $membership->save();



            //Delete link notification for this user
            $notificationLink = new AddCare();
            $notificationLink->source = $this->owner;
            $notificationLink->delete($careUser);
        }

        return;
    }
}