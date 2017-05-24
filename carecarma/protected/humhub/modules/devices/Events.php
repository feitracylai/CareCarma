<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:18 PM
 */

namespace humhub\modules\devices;

use humhub\commands\CronController;
use humhub\libs\Firebase;
use humhub\libs\sendNotificationIOS;
use humhub\modules\dashboard\models\MobileToken;
use humhub\modules\devices\models\DeviceShow;
use humhub\modules\devices\widgets\Notifications;
use humhub\modules\user\models\Profile;
use humhub\modules\user\models\User;
use yii\helpers\Console;
use Yii;
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

    public static function onCronRun($event)
    {
        $controller = $event->sender;
        $interval = CronController::EVENT_ON_HOURLY_RUN;

        $users = User::find()->distinct()->joinWith(['httpSessions', 'profile'])->where(['user.status' => User::STATUS_ENABLED]);
        $totalUsers = $users->count();

        $done = 0;
        $notifySent = 0;
//        $defaultLanguage = Yii::$app->language;

        Console::startProgress($done, $totalUsers, 'Sending report notifications to users... ', false);
        $now = time();
        foreach ($users->each() as $user) {

            $user_tokens = MobileToken::findAll(['user_id' => $user->id]);
            if (count($user_tokens) == 0){
                continue;
            }

            $device_shows = DeviceShow::findAll(['user_id' => $user->id, 'seen' => 0]);
            if (count($device_shows) == 0){
                continue;
            }

            $reportId = [];
            foreach ($device_shows as $device_show){
                $update_interval = $now - strtotime($device_show->updated_at);
                if ($update_interval < 3600){
                    //if the same report user, just send once
                    if (in_array($device_show->report_user_id, $reportId)){
                        continue;
                    }

                    $reportUser = Profile::findOne(['user_id' => $device_show->report_user_id]);
                    $mes = Yii::t('DevicesModule.base', "{ReportF} {ReportL}'s health report is ready.", array("{ReportF}" => $reportUser->firstname, "{ReportL}" => $reportUser->lastname));
                    foreach ($user_tokens as $token){
                        $firebase = new Firebase();
                        $firebase->send($token->device_token, $mes);
//                        $sendIOS = new sendNotificationIOS();
//                        $sendIOS->sendMessage($token->device_token, $mes);
                        $notifySent++;
                    }
                    $reportId[] = $device_show->report_user_id;
                }
            }




            Console::updateProgress( ++$done, $totalUsers);
        }

        Console::endProgress(true);
        $controller->stdout('done - ' . $notifySent . ' report notifications sent.' . PHP_EOL, \yii\helpers\Console::FG_GREEN);
    }


}