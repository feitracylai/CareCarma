<?php

namespace humhub\modules\user\controllers;

//require '/../vendor/autoload.php';

use Yii;
use humhub\modules\user\models\Heartrate;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

/**
 * HeartrateController implements the CRUD actions for heartrate model.
 */
class HeartrateController extends Controller
{
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all heartrate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => heartrate::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single heartrate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

//    /**
//     * Creates a new heartrate model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        Yii::getLogger()->log(print_r(Yii::$app->request->post(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//        $data = Yii::$app->request->post();
//        $json_data = $data['Beacon'];
//        $beacon_list = json_decode($json_data, TRUE);
//
//        foreach ($beacon_list as $beacon) {
//
//            try {
//                $beacon_id = $beacon['beacon_id'];
//            } catch (Exception $e) {
//                $beacon_id = null;
//            }
//            if (array_key_exists("distance", $beacon)) {
//                $distance = $beacon['distance'];
//            } else {
//                $distance = null;
//            }
//            if (array_key_exists("datetime", $beacon)) {
//                $datetime = $beacon['datetime'];
//            } else {
//                $datetime = null;
//            }
//
//            $model = new beacon();
//            $model->user_id = Yii::$app->user->id;
//            $model->beacon_id = $beacon_id;
//            $model->distance = $distance;
//            $model->datetime = $datetime;
//            Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//            $model->save();
//        }
////        if ($model->save()) {
////            return $this->redirect(['view', 'id' => $model->id]);
////        } else {
////            return $this->render('create', [
////                'model' => $model,
////            ]);
////        }
//    }

//    public function actionCreatedy()
//    {
//        Yii::getLogger()->log(print_r(Yii::$app->request->post(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//        $sdk = new \Aws\Sdk([
//            'region'   => 'us-east-1',
//            'version'  => 'latest'
//        ]);
//
//        $dynamodb = $sdk->createDynamoDb();
//        $marshaler = new Marshaler();
//        $tableName = 'beacon';
//
//        $data = Yii::$app->request->post();
//        $json_data = $data['Beacon'];
//        $beacon_list = json_decode($json_data, TRUE);
////        Yii::getLogger()->log(print_r($beacon_list,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        foreach ($beacon_list as $beacon) {
////            Yii::getLogger()->log(print_r($beacon,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//            $beacon_id = $beacon['beacon_id'];
//            $user_id = Yii::$app->user->id;
//            if (array_key_exists("distance", $beacon)) {
//                $distance = $beacon['distance'];
//            } else {
//                $distance = null;
//            }
//            if (array_key_exists("datetime", $beacon)) {
//                $datetime = $beacon['datetime'];
//            } else {
//                $datetime = null;
//            }
//            $json = json_encode([
//                'user_id' => $user_id,
//                'beacon_id' => $beacon_id,
//                'distance' => $distance,
//                'datetime' => $datetime
//            ]);
//            $params = [
//                'TableName' => $tableName,
//                'Item' => $marshaler->marshalJson($json)
//            ];
//
//            try {
//                $result = $dynamodb->putItem($params);
////                Yii::getLogger()->log(print_r($result,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//            } catch (DynamoDbException $e) {
//                echo "Fail\n";
//                break;
//            }
//
//        }
//    }

    /**
     * Updates an existing heartrate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing heartrate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the beacon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return beacon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = heartrate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



//    T0000D000X000  time, hardware_id, heartrate
    public function actionCreateddd()
    {

        ini_set('max_execution_time', 30000);
        date_default_timezone_set('GMT');
        $data = Yii::$app->request->post();
        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $pure_data = $data['Heartrate'];
        $list = array();
        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while(strlen($pure_data) != 0) {
            if($pure_data[0] == "H" and $pure_data[1] == "T"){

                $temp_data = substr($pure_data, 1);
                $pos_next_h = strpos($temp_data, "H");

                if ($pos_next_h == false) {
                    $row = $temp_data;
                }
                else $row = substr($temp_data, 0, $pos_next_h);
                $pos_r = strpos($row, "R");
                $pos_i = strpos($row, "I");
                $time = substr($row, 1, $pos_r-1);

                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;


                $heartrate = substr($row, $pos_r + 1, $pos_i - $pos_r - 1);
                $hardware_id = substr($row, $pos_i + 1);



                $pure_data = substr($pure_data, strlen($row) + 1);
                $shorttime = strtotime($realtime) . substr($time, 5,3);

                $model = new Heartrate();
                $model->user_id = Yii::$app->user->id;
                $model->datetime = $realtime;
                $model->hardware_id = $hardware_id;
                $model->heartrate = $heartrate;
                $model->time = $shorttime;
                Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                $model->save();


            }
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }

//    public function actionCreatenewforjoe()
//    {
//        ini_set('max_execution_time', 30000);
//        date_default_timezone_set('GMT');
//        $data = Yii::$app->request->post();
//        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        $pure_data = $data['Beacon'];
//        $user_id = $data['user_id'];
//        $list = array();
//        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        while(strlen($pure_data) != 0) {
//            if($pure_data[0] == "T"){
//                $temp_data = substr($pure_data, 1);
//                $pos_next_t = strpos($temp_data, "T");
//                if ($pos_next_t == false) $row = $temp_data;
//                else $row = substr($temp_data, 0, $pos_next_t);
//                $pos_d = strpos($row, "D");
//                $pos_x = strpos($row, "X");
//                $time = substr($row, 0, $pos_d);
//
//                $t = time();
//                $yearmonthday = date('Y-m-d',$t);
//                $hoursecond = date('H:i:s', substr($time, 0, 5));
//                $realtime = $yearmonthday . " " . $hoursecond;
//
//                $distance = substr($row, $pos_d + 1, $pos_x - $pos_d - 1);
//                $beacon_id = substr($row, $pos_x + 1);
//
//                $pure_data = substr($pure_data, strlen($row) + 1);
//                $shorttime = strtotime($realtime) . substr($time, 5,3);
//
//                $model = new Beacon();
//                $model->user_id = $user_id;
//                $model->datetime = $realtime;
//                $model->distance = $distance;
//                $model->beacon_id = $beacon_id;
//                $model->time = $shorttime;
//                $model->save();
//            }
//        }
//        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//    }
//
//    public function actionCreatenewforjoenew()
//    {
//        ini_set('max_execution_time', 30000);
//        date_default_timezone_set('GMT');
//        $data = Yii::$app->request->post();
//        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        $pure_data = $data['Beacon'];
//        $user_id = $data['user_id'];
//        $list = array();
//        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        while(strlen($pure_data) != 0) {
//            $temp_data = $pure_data;
//            $pos_next_t = strpos($temp_data, ";");
//            Yii::getLogger()->log(print_r($pos_next_t,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//            if ($pos_next_t == false) $row = $temp_data;
//            else $row = substr($temp_data, 0, $pos_next_t);
//
//            Yii::getLogger()->log(print_r($row,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//            $len = strlen($row);
//
//            $pos_d = strpos($row, ",");
//            $time = substr($row, 0, $pos_d);
//            $row = substr($row, $pos_d+1);
////            Yii::getLogger()->log(print_r($row,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//            $pos_x = strpos($row, ",");
//            $distance = substr($row, 0, $pos_x);
////            Yii::getLogger()->log(print_r($distance,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//            $row = substr($row, $pos_x+1);
//            $beacon_id = $row;
////            Yii::getLogger()->log(print_r($beacon_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//            $realtime = date('Y-m-d H:i:s', substr($time, 0, 10));
//            Yii::getLogger()->log(print_r($realtime,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//            $pure_data = substr($pure_data, $len + 1);
//            $shorttime = $time;
//            Yii::getLogger()->log(print_r($shorttime,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//            $model = new Beacon();
//            $model->user_id = $user_id;
//            $model->datetime = $realtime;
//            $model->distance = $distance;
//            $model->beacon_id = $beacon_id;
//            $model->time = $shorttime;
//            $model->save();
//        }
//        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//    }



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

    public function bytesToShort($bytes, $position) {
        $val = 0;
        $val = $bytes[$position + 1] & 0xFF;
        $val = $val << 8;
        $val |= $bytes[$position] & 0xFF;
        return $val;
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
        Yii::getLogger()->log(print_r($byte_array[$position+1],true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $val = chr($byte_array[$position+1]);
        Yii::getLogger()->log(print_r($val,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        return $val;
    }

    public function bytesToFloat($bytes, $position) {
        $float = unpack('f', pack('c*', $bytes[$position+3], $bytes[$position+2], $bytes[$position+1], $bytes[$position]));
        $float_str = sprintf('%f', $float[1]);
        return $float_str;
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

        $heartrate = $this->bytesToInteger($byte_array, $current);
        Yii::getLogger()->log(print_r($heartrate,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 4;

        $imei = $this->bytesTo8Long($byte_array, $current);
        Yii::getLogger()->log(print_r($imei,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 8;

        $pre_time = (int)substr($time, 0,10);
        $dt = new DateTime("@$pre_time");  // convert UNIX timestamp to PHP DateTime
        $ymd =  $dt->format('Y-m-d H:i:s'); // output = 2017-01-01 00:00:00
        $realtime = $ymd . "." . substr($time, 10,3);

        $model = new Heartrate();
        $model->user_id = Yii::$app->user->id;
        $model->datetime = $realtime;
        $model->hardware_id = $imei;
        $model->heartrate = $heartrate;
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
        while($current < $length) {
            $aorg = $this->bytesToChar($byte_array, $current);
            $current += 2;
            $time = $this->bytesTo6Long($byte_array, $current);
            $current += 6;
            $heartrate = $this->bytesToInteger($byte_array, $current);
            $current += 4;
            $imei = $this->bytesTo8Long($byte_array, $current);
            $current += 8;
            if($aorg == "H"){
                $pre_time = (int)substr($time, 0,10);
                $dt = new DateTime("@$pre_time");  // convert UNIX timestamp to PHP DateTime
                $ymd =  $dt->format('Y-m-d H:i:s'); // output = 2017-01-01 00:00:00
                $realtime = $ymd . "." . substr($time, 10,3);

                $model = new Heartrate();
                $model->user_id = Yii::$app->user->id;
                $model->datetime = $realtime;
                $model->hardware_id = $imei;
                $model->heartrate = $heartrate;
                $model->time = $time;
                $model->save();
            }
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }

}
