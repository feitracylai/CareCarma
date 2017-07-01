<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/24/2017
 * Time: 2:35 PM
 */

namespace humhub\modules\user\controllers;


use humhub\modules\user\models\SensorStepCounter;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii;

class SensorstepController extends Controller
{

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreateddd()
    {

        ini_set('max_execution_time', 30000);
        date_default_timezone_set('GMT');
        $data = Yii::$app->request->post();
        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $pure_data = $data['Steps'];
        $list = array();
        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while(strlen($pure_data) != 0) {
            if($pure_data[0] == "S" and $pure_data[1] == "T"){

                $temp_data = substr($pure_data, 1);
                $pos_next_h = strpos($temp_data, "S");

                if ($pos_next_h == false) {
                    $row = $temp_data;
                }
                else $row = substr($temp_data, 0, $pos_next_h);
                $pos_r = strpos($row, "C");
                $pos_i = strpos($row, "I");
                $time = substr($row, 1, $pos_r-1);

                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;


                $steps = substr($row, $pos_r + 1, $pos_i - $pos_r - 1);
                $hardware_id = substr($row, $pos_i + 1);



                $pure_data = substr($pure_data, strlen($row) + 1);
                $shorttime = strtotime($realtime) . substr($time, 5,3);

                $model = new SensorStepCounter();
                $model->user_id = Yii::$app->user->id;
                $model->datetime = $realtime;
                $model->hardware_id = $hardware_id;
                $model->steps = $steps;
                $model->time = $shorttime;
                Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                $model->save();


            }
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }

    public function actionTestbytes()
    {
        Yii::getLogger()->log(print_r("data",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $pure_data = file_get_contents('php://input');

        $length = count($pure_data);
        $byte_array = unpack('C*', $pure_data);
        Yii::getLogger()->log(print_r($byte_array,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current = 1;
        Yii::getLogger()->log(print_r("beginning",true),yii\log\Logger::LEVEL_INFO,'MyLog');

        $aorg = $this->bytesToChar($byte_array, $current);
        Yii::getLogger()->log(print_r($aorg,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 2;

        $time = $this->bytesTo6Long($byte_array, $current);
        Yii::getLogger()->log(print_r($time,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 6;

        $steps = $this->bytesToInteger($byte_array, $current);
        Yii::getLogger()->log(print_r($steps,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 4;

        $imei = $this->bytesTo8Long($byte_array, $current);
        Yii::getLogger()->log(print_r($imei,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 8;

        $pre_time = (int)substr($time, 0,10);
        $dt = new \DateTime("@$pre_time");  // convert UNIX timestamp to PHP DateTime
        $ymd =  $dt->format('Y-m-d H:i:s'); // output = 2017-01-01 00:00:00
        $realtime = $ymd . "." . substr($time, 10,3);

        $model = new SensorStepCounter();
        $model->user_id = Yii::$app->user->id;
        $model->datetime = $realtime;
        $model->hardware_id = $imei;
        $model->heartrate = $steps;
        $model->time = $time;
        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $model->save();
    }


    public function actionCreatebytes()
    {
        ini_set('max_execution_time', 30000);
        $pure_data = file_get_contents('php://input');
        $byte_array = unpack('C*', $pure_data);
        $length = count($byte_array);
        $current = 1;
        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        Yii::getLogger()->log(print_r([Yii::$app->user->id, $length],true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while($current < $length) {
            $aorg = $this->bytesToChar($byte_array, $current);
            $current += 2;
            $time = $this->bytesTo6Long($byte_array, $current);
            $current += 6;
            $steps = $this->bytesToInteger($byte_array, $current);
            $current += 4;
            $imei = $this->bytesTo8Long($byte_array, $current);
            $current += 8;
            if($aorg == "S"){
                $pre_time = (int)substr($time, 0,10);
                $dt = new \DateTime("@$pre_time");  // convert UNIX timestamp to PHP DateTime
                $ymd =  $dt->format('Y-m-d H:i:s'); // output = 2017-01-01 00:00:00
                $realtime = $ymd . "." . substr($time, 10,3);

                $model = new SensorStepCounter();
                $model->user_id = Yii::$app->user->id;
                $model->datetime = $realtime;
                $model->hardware_id = $imei;
                $model->steps = $steps;
                $model->time = $time;
                $model->save();
            }
            Yii::getLogger()->log(print_r($imei,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }


    public function bytesToInteger($bytes, $position) {
//        $i = unpack("L",pack("C*",$ar[1],$ar[2],$ar[3],$ar[4]));
//        $val = 0;
//        $val = $bytes[$position + 3] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position + 2] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position + 1] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position] & 0xff;
//        return $val;
        $int = unpack('I', pack('c*', $bytes[$position+3], $bytes[$position+2], $bytes[$position+1], $bytes[$position]));
        $int_str = (string)$int[1];
        return $int_str;
    }


    public function bytesTo6Long($bytes, $position) {
        $long = unpack("Q",pack("C*",$bytes[$position+5],$bytes[$position+4],$bytes[$position+3],$bytes[$position+2],$bytes[$position+1],$bytes[$position],0,0));
        $long_str = sprintf('%.0f', $long[1]);
//        $val = 0;
//        $val = $bytes[$position + 5] & 0xff;
//        $val <<= 8;
//        $val = $bytes[$position + 4] & 0xff;
//        $val <<= 8;
//        $val = $bytes[$position + 3] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position + 2] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position + 1] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position] & 0xff;
//        return $val;
        return $long_str;
    }

    public function bytesTo8Long($bytes, $position) {
        $long = unpack("Q",pack("C*",$bytes[$position+7],$bytes[$position+6],$bytes[$position+5],$bytes[$position+4],$bytes[$position+3],$bytes[$position+2],$bytes[$position+1],$bytes[$position]));
        $long_str = sprintf('%.0f', $long[1]);
//        $val = 0;
//        $val = $bytes[$position + 7] & 0xff;
//        $val <<= 8;
//        $val = $bytes[$position + 6] & 0xff;
//        $val <<= 8;
//        $val = $bytes[$position + 5] & 0xff;
//        $val <<= 8;
//        $val = $bytes[$position + 4] & 0xff;
//        $val <<= 8;
//        $val = $bytes[$position + 3] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position + 2] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position + 1] & 0xff;
//        $val <<= 8;
//        $val |= $bytes[$position] & 0xff;
        return $long_str;
    }

    public function bytesToChar($byte_array, $position) {
//        Yii::getLogger()->log(print_r($byte_array[$position+1],true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $val = chr($byte_array[$position+1]);
//        Yii::getLogger()->log(print_r($val,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        return $val;
    }

}