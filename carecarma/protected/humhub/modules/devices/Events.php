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

    public static function onProfileMenuInit($event)
    {
        $user = $event->sender->user;
        //only user can see his own report
        if ($user->id == Yii::$app->user->id) {
            $event->sender->addItem(array(
                'label' => Yii::t('DevicesModule.base', 'Health Report'),
                'icon' => '<i class="fa fa-bar-chart"></i>',
                'group' => 'profile',
                'sortOrder' => 250,
                'url' => $user->createUrl('/devices/view/index'),
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'devices'),
            ));
        }
    }


}