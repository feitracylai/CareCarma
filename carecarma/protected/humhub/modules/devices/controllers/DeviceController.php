<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/17/2017
 * Time: 3:58 PM
 */

namespace humhub\modules\devices\controllers;

use Yii;
use humhub\components\Controller;
use humhub\modules\devices\models\DeviceTimezone;
use yii\log\Logger;

class DeviceController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionTimezone()
    {
        date_default_timezone_set('GMT');
        $data = Yii::$app->request->post();

        $hardware_id = $data['IMEI'];
        $timezone = $data['timezone'];

        $model = new DeviceTimezone();
        $model->user_id = Yii::$app->user->id;
        $model->hardware_id = $hardware_id;
        $model->timezone = $timezone;
        $model->updated_time = $this->getMillisecond(microtime());
        Yii::getLogger()->log(print_r($model, true), Logger::LEVEL_INFO, 'MyLog');
        $model->save();

    }

    public function getMillisecond($time)
    {

        list($t1, $t2) = explode(' ', $time);
        return $t2 . '' .  ceil( ($t1 * 1000) );
    }
}