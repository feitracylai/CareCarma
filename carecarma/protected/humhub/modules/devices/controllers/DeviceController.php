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
//        Yii::getLogger()->log('timezone', Logger::LEVEL_INFO, 'MyLog');
        $data = Yii::$app->request->post();

        $hardware_id = $data['IMEI'];
        $timezone = $data['timezone'];
        $time = $data['time'];

        $model = DeviceTimezone::find()->where(['user_id' => Yii::$app->user->id, 'hardware_id'=>$hardware_id])->orderBy('updated_time DESC')->one();
        if ($model == null || $model->timezone != $timezone){
            $model = new DeviceTimezone();
            $model->user_id = Yii::$app->user->id;
            $model->hardware_id = $hardware_id;
            $model->timezone = $timezone;
        }

//        $model->updated_time = $this->getMillisecond(microtime());
        $model->updated_time = $time;
//        Yii::getLogger()->log(print_r($model, true), Logger::LEVEL_INFO, 'MyLog');
        $model->save();

    }


}