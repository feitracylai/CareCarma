<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:18 PM
 */

namespace humhub\modules\devices;

use humhub\modules\devices\widgets\Notifications;
use Yii;
use yii\helpers\Url;
use yii\log\Logger;


class Events extends \yii\base\Object
{

    public static function onNotificationAddonInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addWidget(Notifications::className(), array(), array('sortOrder' => 110));
    }


}