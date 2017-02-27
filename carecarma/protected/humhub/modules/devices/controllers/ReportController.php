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

class ReportController extends Controller
{


    public function actionReportList()
    {

        $device_shows = DeviceShow::find()->where(['user_id' => Yii::$app->user->id])->orderBy('space_id')->all();


        return $this->renderAjax('reportList', array('device_shows' => $device_shows));
    }

    public function actionGetNewReportCountJson()
    {

        Yii::$app->response->format = 'json';


        $device_shows = DeviceShow::findAll(['user_id' => Yii::$app->user->id, 'seen' => 0]);
        $count = count($device_shows);

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