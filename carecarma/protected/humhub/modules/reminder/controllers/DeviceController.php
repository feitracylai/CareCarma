<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 5/22/2017
 * Time: 11:56 AM
 */

namespace humhub\modules\reminder\controllers;


use humhub\components\Controller;
use humhub\libs\GCM;
use humhub\modules\reminder\models\ReminderDevice;
use humhub\modules\reminder\models\ReminderDeviceTime;
use humhub\modules\user\models\Device;
use humhub\modules\user\models\User;
use Yii;

class DeviceController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionGetreminders()
    {
        $data = Yii::$app->request->post();
//        Yii::getLogger()->log($data, yii\log\Logger::LEVEL_INFO, 'MyLog');
        $user = User::findOne(['username' => $data['username']]);

        if ($user == null){
            return 'user name is error.';
        }

        $device = Device::findOne(['device_id' => $data['device_id'], 'user_id' => $user->id, 'activate' => 1]);

        if ($device == null){
            return 'device id is error.';
        }

        $reminders = ReminderDevice::findAll(['user_id' => $user->id]);
        if (count($reminders) == 0){
            return 'You do not have any reminders.';
        }

//        $data = array();
//        $data['type'] = 'reminder, all';
//        $data['data'] = array();
        foreach ($reminders as $reminder){
            foreach ($reminder->times as $reminder_time){
                /***not send expired recurring reminders***/
                if ($reminder_time->remove_sent == 1){
                    break;
                }

                $info = array();
                $info['type'] = 'reminder,add';
                $info['id'] = $reminder_time->id;

                $info['title'] = $reminder->title;
                $info['description'] = $reminder->description;
                //$data['send'] = $reminder->send->firstname;

                $info['hour'] = date_format(date_create($reminder_time->time), "H");
                $info['minutes'] = date_format(date_create($reminder_time->time), "i");

                if ($reminder_time->repeat == 0){

                    $info['repeat'] = $reminder_time::REMIND_ONCE;
                    $info['date'] = date_format(date_create($reminder_time->date), "d");
                    $info['month'] = date_format(date_create($reminder_time->date), "m");
                    $info['year'] = date_format(date_create($reminder_time->date), "Y");


                } elseif ($reminder_time->repeat == 1){
                    /******if it is everyday**********/
                    if ($reminder_time->day == 0){
                        $info['repeat'] = $reminder_time::REMIND_EVERYDAY;
                    } else {
                        $info['repeat'] = $reminder_time::REMIND_DAY;
                        $info['day'] = $reminder_time->day;
                    }

                }

                $gcm = new GCM();
                $gcm->send($device->gcmId, $info);
            }
        }


//        Yii::getLogger()->log($result, Logger::LEVEL_INFO, 'MyLog');
//        if ($result['success'] == 0) {
//            return $result['results'][0]['error'];
//        }

        return 'success';
    }
    

}