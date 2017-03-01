<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/13/2017
 * Time: 4:52 PM
 */

namespace humhub\modules\devices\controllers;

use humhub\modules\devices\models\DeviceShow;
use Yii;
use humhub\components\Controller;
use yii\log\Logger;

class ReportController extends Controller
{


    public function actionReportList()
    {
        $user_id = Yii::$app->user->id;
        $device_shows = DeviceShow::find()->where(['user_id' => $user_id])->orderBy('space_id')->all();

        $array = [];
        $repeat_array = [];
        $count = 0;
        foreach ($device_shows as $device_show)
        {
            $report_user_id = $device_show->report_user_id;
            $id = $device_show->id;

            $same = DeviceShow::findAll(['report_user_id' => $report_user_id, 'user_id' => $user_id]);
            if (count($same) > 1){
                $repeat_array[$report_user_id][$count] = $device_show->updated_at;
            } else {
                $array[] = $id;
            }

            $count++;
        }

//        Yii::getLogger()->log($repeat_array, Logger::LEVEL_INFO, 'MyLog');
//        Yii::getLogger()->log($array, Logger::LEVEL_INFO, 'MyLog');

        foreach ($repeat_array as $repeat_item){
            //choose latest time update device of the same report user
            $max_count = array_search(max($repeat_item), $repeat_item);
//            Yii::getLogger()->log([max($repeat_item), $max_count, $device_shows[$max_count]->id], Logger::LEVEL_INFO, 'MyLog');
            $array[] = $device_shows[$max_count]->id;
        }
//        Yii::getLogger()->log($array, Logger::LEVEL_INFO, 'MyLog');
        $last_device_shows = DeviceShow::findAll($array);

        return $this->renderAjax('reportList', array('device_shows' => $last_device_shows));
    }

    public function actionGetNewReportCountJson()
    {

        Yii::$app->response->format = 'json';

        $user_id = Yii::$app->user->id;
        $device_shows = DeviceShow::findAll(['user_id' => $user_id, 'seen' => 0]);
        $report_user_array = [];
        foreach ($device_shows as $device_show){
            $report_user_array[$device_show->id] = $device_show->report_user_id;
        }
        $unique_array = array_unique($report_user_array);
        $keys = array_keys($unique_array);
        $unique_device_shows = DeviceShow::findAll($keys);

        $count = count($unique_device_shows);

        $json = array();
        $json['newReport'] = $count;

        return $json;
    }

    public function actionMarkAsSeen()
    {
        Yii::$app->response->format = 'json';
        $count = DeviceShow::updateAll(['seen' => 1], ['user_id' => Yii::$app->user->id]);

        return [
            'success' => true,
            'count' => $count
        ];
    }
}