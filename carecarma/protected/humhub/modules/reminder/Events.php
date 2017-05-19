<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:18 PM
 */

namespace humhub\modules\reminder;

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

        $event->sender->addWidget(widgets\ShowReminder::className(), array(), array('sortOrder' => 150));
    }

    public static function onTopMenuInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addItem(array(
            'label' => Yii::t('ReminderModule.base', 'Reminder'),
            'url' => Url::to(['/reminder/remind/index']),
            'icon' => '<i class="fa fa-lightbulb-o"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'reminder'),
            'sortOrder' => 310,
        ));
    }

    public static function setCareRemind($event)
    {
        $space = $event->sender->space;


        $event->sender->addItem(array(
            'label' => Yii::t('ReminderModule.base', 'Set Reminder'),
            'url' => $space->createUrl('/reminder/receiver/index', ['rguid' => Yii::$app->request->get('rguid')]),
            'sortOrder' => '350',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'reminder'),
        ));
    }

}