<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:18 PM
 */

namespace humhub\modules\reminder;

use humhub\commands\CronController;
use humhub\libs\GCM;
use humhub\modules\devices\models\DeviceTimezone;
use humhub\modules\reminder\models\ReminderDevice;
use humhub\modules\reminder\models\ReminderDeviceTime;
use humhub\modules\user\models\Device;
use Yii;
use yii\helpers\Console;
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
            'label' => Yii::t('ReminderModule.base', 'Set Reminders'),
            'url' => $space->createUrl('/reminder/receiver/index', ['rguid' => Yii::$app->request->get('rguid')]),
            'sortOrder' => '350',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'reminder'),
        ));
    }

    /**********tell device to remove expired reminders everyday******/
    public static function onCronRun($event)
    {
        $controller = $event->sender;

        $devices = Device::findAll(['activate' => 1]);
        $deviceCount = count($devices);

        $done = 0;
        $notifyDevice = 0;

        Console::startProgress($done, $deviceCount, 'Notify devices to remove the expired reminders ... ', false);
        foreach ($devices as $device) {
            /***find user's reminders****/
            $reminders = ReminderDevice::findAll(['user_id' => $device->user_id]);
            if (count($reminders) != 0) {

                /***check now date time in the device timezone****/
                $deviceTimezone = DeviceTimezone::find()->where(['hardware_id' => $device->hardware_id])->orderBy('updated_time DESC')->one();
                if ($deviceTimezone != null){
                    switch ($deviceTimezone->timezone){
                        case 'PST': $realTimezone = 'PST8PDT'; break;
                        case 'CST' : $realTimezone = 'CST6CDT'; break;
                        case 'EST' : $realTimezone = 'EST5EDT'; break;
                        case  'MST' : $realTimezone = 'MST7MDT'; break;
                        default: $realTimezone = $deviceTimezone->timezone;
                    }
                    date_default_timezone_set($realTimezone);
                } elseif ($device->user->time_zone != "") {
                    date_default_timezone_set($device->user->time_zone);
                } else {
                    $time_zone = \humhub\models\Setting::Get('timeZone');
                    date_default_timezone_set($time_zone);
                }
                $today = date('M d, Y');
                $time = date('H:i');
//                Yii::getLogger()->log(['device_id='.$device->id, 'user_id='.$device->user_id, date_default_timezone_get(), $today.' '.$time], Logger::LEVEL_INFO, 'MyLog');

                /***find expired times in this user's reminders****/
                foreach ($reminders as $reminder){
                    foreach ($reminder->getTimes()->each() as $reminder_time){
                        $expired = 0;
                        /***check expired time before now***/
                        if ($reminder_time->repeat == 1 && $reminder_time->remove_sent == '0') {
                            if (strtotime($reminder_time->deadline) < strtotime($today)) {
//                            Yii::getLogger()->log($reminder_time->id.'have deadline', Logger::LEVEL_INFO, 'MyLog');
//                            Yii::getLogger()->log([strtotime($reminder_time->deadline), strtotime($today)], Logger::LEVEL_INFO, 'MyLog');
//                            Yii::getLogger()->log([strtotime($reminder_time->time), strtotime($time)], Logger::LEVEL_INFO, 'MyLog');
                                if (strtotime($reminder_time->deadline) < strtotime($today)) {
//                                Yii::getLogger()->log('before today deadline', Logger::LEVEL_INFO, 'MyLog');
                                    $expired = 1;
                                } elseif (strtotime($reminder_time->deadline) == strtotime($today) && strtotime($reminder_time->time) <= strtotime($time)) {
//                                Yii::getLogger()->log('today deadline', Logger::LEVEL_INFO, 'MyLog');
                                    $expired = 1;
                                }
                            }
                        }
                        /***sent GCM to remove reminder***/
                        if ($expired == 1){
                            $gcm = new GCM();
                            $gcm->send($device->gcmId,['type' => 'reminder,delete','id' => $reminder_time->id]);
                            $notifyDevice++;

                            $reminder_time->remove_sent = 1;
                            $reminder_time->save();
                        }


                    }
                }
            }


            Console::updateProgress( ++$done, $deviceCount);
        }

        Console::endProgress(true);
        $controller->stdout('done - ' . $notifyDevice . ' reminders removed.' . PHP_EOL, \yii\helpers\Console::FG_GREEN);
    }

}
