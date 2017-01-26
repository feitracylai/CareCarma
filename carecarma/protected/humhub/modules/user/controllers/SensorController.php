<?php

namespace humhub\modules\user\controllers;

//require '/../vendor/autoload.php';

use Yii;
use humhub\modules\user\models\Sensor;
use yii\data\ActiveDataProvider;
use yii\log\Logger;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

/**
 * SensorController implements the CRUD actions for sensor model.
 */
class SensorController extends Controller
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
     * Lists all sensor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => sensor::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single sensor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new sensor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        Yii::getLogger()->log(print_r("JSON:beginning",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $data = Yii::$app->request->post();
        $json_data = $data['Sensor'];
        $sensor_list = json_decode($json_data, TRUE);

        foreach ($sensor_list as $sensor) {

            if (array_key_exists("accelX", $sensor)) {
                $accelX = $sensor['accelX'];
            } else {
                $accelX = null;
            }
            if (array_key_exists("accelY", $sensor)) {
                $accelY = $sensor['accelY'];
            } else {
                $accelY = null;
            }
            if (array_key_exists("accelZ", $sensor)) {
                $accelZ = $sensor['accelZ'];
            } else {
                $accelZ = null;
            }

            if (array_key_exists("GyroX", $sensor)) {
                $gyroX = $sensor['GyroX'];
            } else {
                $gyroX = null;
            }
            if (array_key_exists("GyroY", $sensor)) {
                $gyroY = $sensor['GyroY'];
            } else {
                $gyroY = null;
            }
            if (array_key_exists("GyroZ", $sensor)) {
                $gyroZ = $sensor['GyroZ'];
            } else {
                $gyroZ = null;
            }



            if (array_key_exists("CompX", $sensor)) {
                $compX = $sensor['CompX'];
            } else {
                $compX = null;
            }
            if (array_key_exists("CompY", $sensor)) {
                $compY = $sensor['CompY'];
            } else {
                $compY = null;
            }
            if (array_key_exists("CompZ", $sensor)) {
                $compZ = $sensor['CompZ'];
            } else {
                $compZ = null;
            }

            if (array_key_exists("datetime", $sensor)) {
                $datetime = $sensor['datetime'];
            } else {
                $datetime = null;
            }

            $model = new sensor();
            $model->user_id = Yii::$app->user->id;
            $model->datetime = $datetime;
            $model->accelX = $accelX;
            $model->accelY = $accelY;
            $model->accelZ = $accelZ;
            $model->GyroX = $gyroX;
            $model->GyroY = $gyroY;
            $model->GyroZ = $gyroZ;
            $model->CompX = $compX;
            $model->CompY = $compY;
            $model->CompZ = $compZ;
            $model->save();
        }
        Yii::getLogger()->log(print_r("JSON:end",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        if ($model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }


    public function actionCreatedy()
    {

        Yii::getLogger()->log(print_r(Yii::$app->request->post(),true),yii\log\Logger::LEVEL_INFO,'MyLog');

        $sdk = new \Aws\Sdk([
            'region'   => 'us-east-1',
            'version'  => 'latest'
        ]);
        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();
        $tableName = 'sensor';

        $data = Yii::$app->request->post();
        $json_data = $data['Sensor'];
        $sensor_list = json_decode($json_data, TRUE);
//        Yii::getLogger()->log(print_r($sensor_list,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        foreach ($sensor_list as $sensor) {
//            Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            $user_id = Yii::$app->user->id;
            if (array_key_exists("accelX", $sensor)) {
                $accelX = $sensor['accelX'];
            } else {
                $accelX = null;
            }
            if (array_key_exists("accelY", $sensor)) {
                $accelY = $sensor['accelY'];
            } else {
                $accelY = null;
            }
            if (array_key_exists("accelZ", $sensor)) {
                $accelZ = $sensor['accelZ'];
            } else {
                $accelZ = null;
            }

            if (array_key_exists("GyroX", $sensor)) {
                $gyroX = $sensor['GyroX'];
            } else {
                $gyroX = null;
            }
            if (array_key_exists("GyroY", $sensor)) {
                $gyroY = $sensor['GyroY'];
            } else {
                $gyroY = null;
            }
            if (array_key_exists("GyroZ", $sensor)) {
                $gyroZ = $sensor['GyroZ'];
            } else {
                $gyroZ = null;
            }



            if (array_key_exists("CompX", $sensor)) {
                $compX = $sensor['CompX'];
            } else {
                $compX = null;
            }
            if (array_key_exists("CompY", $sensor)) {
                $compY = $sensor['CompY'];
            } else {
                $compY = null;
            }
            if (array_key_exists("CompZ", $sensor)) {
                $compZ = $sensor['CompZ'];
            } else {
                $compZ = null;
            }

            if (array_key_exists("datetime", $sensor)) {
                $datetime = $sensor['datetime'];
            } else {
                $datetime = null;
            }
            $json = json_encode([
                'user_id' => $user_id,
                'datetime' => $datetime,
                'accelX' => $accelX,
                'accelY' => $accelY,
                'accelZ' => $accelZ,
                'compX' => $compX,
                'compY' => $compY,
                'compZ' => $compZ,
                'gyroX' => $gyroX,
                'gyroY' => $gyroY,
                'gyroZ' => $gyroZ
            ]);
            $params = [
                'TableName' => $tableName,
                'Item' => $marshaler->marshalJson($json)
            ];

            try {
                $result = $dynamodb->putItem($params);
                Yii::getLogger()->log(print_r($result,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            } catch (DynamoDbException $e) {
                echo "Fail\n";
                break;
            }
        }
    }

    public function actionCreatenew()
    {
        ini_set('max_execution_time', 30000);
        date_default_timezone_set('GMT');
        $data = Yii::$app->request->post();
        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $pure_data = $data['Sensor'];
        $list = array();
        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while(strlen($pure_data) != 0) {
            
            if($pure_data[0] == "A"){
                $temp_data = substr($pure_data, 1);
                $pos_a = strpos($temp_data, "A");
                $pos_g = strpos($temp_data, "G");
                if ($pos_a == false and $pos_g == false) $row = $temp_data;
                else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                else {
                    if ($pos_a > $pos_g) $pos = $pos_g;
                    else $pos = $pos_a;
                    $row = substr($temp_data, 0, $pos);
                }
                $pos_x = strpos($row, "X");
                $pos_y = strpos($row, "Y");
                $pos_z = strpos($row, "Z");
                $time = substr($row, 1, $pos_x-1);
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));

                // ***********************************************
                // realtime is the "datetime" in table 'sensor'
                $realtime = $yearmonthday . " " . $hoursecond;
                // ***********************************************

                $ax = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                $ay = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                $az = substr($row, $pos_z+1);
                // remove last row
                $pure_data = substr($pure_data, strlen($row)+1);

                // ***********************************************
                // shorttime is the "time" in table 'sensor'
                $shorttime = strtotime($realtime) . substr($time, 5,3);
                // ***********************************************

                // ********************************************************************************
                // see the next "G" row, check if the time is the same as the current "A" row
                // ********************************************************************************
                if ($pure_data[0] == "G") {
                    $pos_x = strpos($pure_data, "X");
                    // new_time is the time of next "G" row
                    $new_time = substr($pure_data, 2, $pos_x - 2);
                    // if the new_time of "G" row = time of "A" row, we need to store it into one row!!!!!!!!!!
                    if ($new_time == $time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");

                        $gx = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                        $gy = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                        $gz = substr($row, $pos_z + 1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($az)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();

                    }
                    else if((int)$new_time < (int)$time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");
                        $time2 = substr($row, 1, $pos_x-1);
                        $t2 = time();
                        $yearmonthday2 = date('Y-m-d',$t2);
                        $hoursecond2 = date('H:i:s', substr($time2, 0, 5));

                        $realtime2 = $yearmonthday2 . " " . $hoursecond2;

                        $gx = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                        $gy = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                        $gz = substr($row, $pos_z+1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $shorttime2 = strtotime($realtime2) . substr($time2, 5,3);

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime2;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime2 ;
                        $model->save();

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->time = $shorttime ;
                        $model->save();
                    }
                    else {
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->time = $shorttime ;
                        $model->save();
                    }
                }
                else {
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = $realtime;
                    $model->accelX = $ax;
                    $model->accelY = $ay;
                    if (!is_numeric(substr($az,strlen($az)-1,1))) {
                        $az = substr($az, 0, -1);
                    }
                    $model->accelZ = $az;
                    $model->time = $shorttime ;
                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                }


//                $sensor = Sensor::findOne(['time' => $shorttime]);
////                Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//                if (sizeof($sensor) == 0) {
////                    Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model = new Sensor();
//                    $model->user_id = Yii::$app->user->id;
//                    $model->datetime = $realtime;
//                    $model->accelX = $ax;
//                    $model->accelY = $ay;
//                    $model->accelZ = $az;
//                    $model->time = $shorttime ;
////                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model->save();
//                }
//                else {
//                    $sensor->accelX = $ax;
//                    $sensor->accelY = $ay;
//                    $sensor->accelZ = $az;
//                    $sensor->save();
//                }
        }
            else if($pure_data[0] == "G") {
                $temp_data = substr($pure_data, 1);
                $pos_a = strpos($temp_data, "A");
                $pos_g = strpos($temp_data, "G");
                if ($pos_a == false and $pos_g == false) $row = $temp_data;
                else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                else {
                    if ($pos_a > $pos_g) $pos = $pos_g;
                    else $pos = $pos_a;
                    $row = substr($temp_data, 0, $pos);
                }
                $pos_x = strpos($row, "X");
                $pos_y = strpos($row, "Y");
                $pos_z = strpos($row, "Z");
                $time = substr($row, 1, $pos_x - 1);
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;

                $gx = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                $gy = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                $gz = substr($row, $pos_z + 1);
                $pure_data = substr($pure_data, strlen($row) + 1);
                $shorttime = strtotime($realtime) . substr($time, 5,3);

                if ($pure_data[0] == "A") {
                    $pos_x = strpos($pure_data, "X");
                    $new_time = substr($pure_data, 2, $pos_x - 2);
                    if ($new_time == $time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");

                        $ax = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                        $ay = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                        $az = substr($row, $pos_z + 1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
//                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();

                    }

                    else if((int)$new_time < (int)$time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");
                        $time2 = substr($row, 1, $pos_x-1);
                        $t2 = time();
                        $yearmonthday2 = date('Y-m-d',$t2);
                        $hoursecond2 = date('H:i:s', substr($time2, 0, 5));

                        $realtime2 = $yearmonthday2 . " " . $hoursecond2;

                        $ax = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                        $ay = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                        $az = substr($row, $pos_z+1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $shorttime2 = strtotime($realtime2) . substr($time2, 5,3);

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime2;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->time = $shorttime2 ;
                        $model->save();

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();
                    }

                    else {
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();
                    }
                }
                else {
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = $realtime;
                    $model->GyroX = $gx;
                    $model->GyroY = $gy;
                    if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                        $gz = substr($gz, 0, -1);
                    }
                    $model->GyroZ = $gz;
                    $model->time = $shorttime ;
                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                }


//                $sensor = Sensor::findOne(['time' => $shorttime]);
////                Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//                if (sizeof($sensor) == 0) {
////                    Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model = new Sensor();
//                    $model->user_id = Yii::$app->user->id;
//                    $model->datetime = $realtime;
//                    $model->GyroX = $gx;
//                    $model->GyroY = $gy;
//                    $model->GyroZ = $gz;
//                    $model->time = $shorttime ;
////                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model->save();
//                }
//                else {
//                    $sensor->GyroX = $gx;
//                    $sensor->GyroY = $gy;
//                    $sensor->GyroZ = $gz;
//                    $sensor->save();
//                }
            }
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }




    public function actionCreateddd()
    {
        ini_set('max_execution_time', 30000);
        date_default_timezone_set('GMT');
        $data = Yii::$app->request->post();
        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $pure_data = $data['Sensor'];
        $list = array();
        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while(strlen($pure_data) != 0) {

            if($pure_data[0] == "A"){
                $temp_data = substr($pure_data, 1);
                $pos_a = strpos($temp_data, "A");
                $pos_g = strpos($temp_data, "G");
                if ($pos_a == false and $pos_g == false) $row = $temp_data;
                else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                else {
                    if ($pos_a > $pos_g) $pos = $pos_g;
                    else $pos = $pos_a;
                    $row = substr($temp_data, 0, $pos);
                }
//                Yii::getLogger()->log($row, yii\log\Logger::LEVEL_INFO,'MyLog');
                $pos_x = strpos($row, "X");
                $pos_y = strpos($row, "Y");
                $pos_z = strpos($row, "Z");
                $pos_i = strpos($row, "I");
                $time = substr($row, 1, $pos_x-1);
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));

                // ***********************************************
                // realtime is the "datetime" in table 'sensor'
                $realtime = $yearmonthday . " " . $hoursecond;
                // ***********************************************

                $ax = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                $ay = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                $az = substr($row, $pos_z+1, $pos_i-$pos_z-1);
                $hardware_id = substr($row, $pos_i + 1);
                // remove last row
                $pure_data = substr($pure_data, strlen($row)+1);

                // ***********************************************
                // shorttime is the "time" in table 'sensor'
                $shorttime = strtotime($realtime) . substr($time, 5,3);
                // ***********************************************

                // ********************************************************************************
                // see the next "G" row, check if the time is the same as the current "A" row
                // ********************************************************************************
                if ($pure_data[0] == "G") {
                    $pos_x = strpos($pure_data, "X");
                    // new_time is the time of next "G" row
                    $new_time = substr($pure_data, 2, $pos_x - 2);
                    // if the new_time of "G" row = time of "A" row, we need to store it into one row!!!!!!!!!!
                    if ($new_time == $time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");
                        $pos_i = strpos($row, "I");

                        $gx = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                        $gy = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                        $gz = substr($row, $pos_z + 1, $pos_i - $pos_z - 1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($az)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->hardware_id = $hardware_id;
                        $model->time = $shorttime ;
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();

                    }
                    else if((int)$new_time < (int)$time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");
                        $pos_i = strpos($row, "I");
                        $time2 = substr($row, 1, $pos_x-1);
                        $t2 = time();
                        $yearmonthday2 = date('Y-m-d',$t2);
                        $hoursecond2 = date('H:i:s', substr($time2, 0, 5));

                        $realtime2 = $yearmonthday2 . " " . $hoursecond2;

                        $gx = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                        $gy = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                        $gz = substr($row, $pos_z+1, $pos_i-$pos_z-1);
                        $hardware_id2 = substr($row, $pos_i+1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $shorttime2 = strtotime($realtime2) . substr($time2, 5,3);

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime2;
                        $model->hardware_id = $hardware_id2;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime2 ;
                        $model->save();

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->hardware_id = $hardware_id;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->time = $shorttime ;
                        $model->save();
                    }
                    else {
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->hardware_id = $hardware_id;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->time = $shorttime ;
                        $model->save();
                    }
                }
                else {
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = $realtime;
                    $model->hardware_id = $hardware_id;
                    $model->accelX = $ax;
                    $model->accelY = $ay;
                    if (!is_numeric(substr($az,strlen($az)-1,1))) {
                        $az = substr($az, 0, -1);
                    }
                    $model->accelZ = $az;
                    $model->time = $shorttime ;
//                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                }


//                $sensor = Sensor::findOne(['time' => $shorttime]);
////                Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//                if (sizeof($sensor) == 0) {
////                    Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model = new Sensor();
//                    $model->user_id = Yii::$app->user->id;
//                    $model->datetime = $realtime;
//                    $model->accelX = $ax;
//                    $model->accelY = $ay;
//                    $model->accelZ = $az;
//                    $model->time = $shorttime ;
////                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model->save();
//                }
//                else {
//                    $sensor->accelX = $ax;
//                    $sensor->accelY = $ay;
//                    $sensor->accelZ = $az;
//                    $sensor->save();
//                }
            }
            else if($pure_data[0] == "G") {
                $temp_data = substr($pure_data, 1);
                $pos_a = strpos($temp_data, "A");
                $pos_g = strpos($temp_data, "G");
                if ($pos_a == false and $pos_g == false) $row = $temp_data;
                else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                else {
                    if ($pos_a > $pos_g) $pos = $pos_g;
                    else $pos = $pos_a;
                    $row = substr($temp_data, 0, $pos);
                }
                $pos_x = strpos($row, "X");
                $pos_y = strpos($row, "Y");
                $pos_z = strpos($row, "Z");
                $pos_i = strpos($row, "I");
                $time = substr($row, 1, $pos_x - 1);
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;

                $gx = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                $gy = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                $gz = substr($row, $pos_z + 1, $pos_i - $pos_z - 1);
                $hardware_id = substr($row, $pos_i + 1);
                $pure_data = substr($pure_data, strlen($row) + 1);
                $shorttime = strtotime($realtime) . substr($time, 5,3);

                if ($pure_data[0] == "A") {
                    $pos_x = strpos($pure_data, "X");
                    $new_time = substr($pure_data, 2, $pos_x - 2);
                    if ($new_time == $time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");
                        $pos_i = strpos($row, "I");

                        $ax = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                        $ay = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                        $az = substr($row, $pos_z + 1, $pos_i - $pos_z - 1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->hardware_id = $hardware_id;
                        $model->time = $shorttime ;
//                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();

                    }

                    else if((int)$new_time < (int)$time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");
                        $pos_i = strpos($row, "I");
                        $time2 = substr($row, 1, $pos_x-1);
                        $t2 = time();
                        $yearmonthday2 = date('Y-m-d',$t2);
                        $hoursecond2 = date('H:i:s', substr($time2, 0, 5));

                        $realtime2 = $yearmonthday2 . " " . $hoursecond2;

                        $ax = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                        $ay = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                        $az = substr($row, $pos_z+1, $pos_i-$pos_y-1);
                        $hardware_id2 = substr($row, $pos_i+1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $shorttime2 = strtotime($realtime2) . substr($time2, 5,3);

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime2;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->hardware_id = $hardware_id2;
                        $model->time = $shorttime2 ;
                        $model->save();

                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->hardware_id = $hardware_id;
                        $model->time = $shorttime ;
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();
                    }

                    else {
                        $model = new Sensor();
                        $model->user_id = Yii::$app->user->id;
                        $model->datetime = $realtime;
                        $model->hardware_id = $hardware_id;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();
                    }
                }
                else {
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = $realtime;
                    $model->hardware_id = $hardware_id;
                    $model->GyroX = $gx;
                    $model->GyroY = $gy;
                    if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                        $gz = substr($gz, 0, -1);
                    }
                    $model->GyroZ = $gz;
                    $model->time = $shorttime ;
                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                }


//                $sensor = Sensor::findOne(['time' => $shorttime]);
////                Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//                if (sizeof($sensor) == 0) {
////                    Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model = new Sensor();
//                    $model->user_id = Yii::$app->user->id;
//                    $model->datetime = $realtime;
//                    $model->GyroX = $gx;
//                    $model->GyroY = $gy;
//                    $model->GyroZ = $gz;
//                    $model->time = $shorttime ;
////                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model->save();
//                }
//                else {
//                    $sensor->GyroX = $gx;
//                    $sensor->GyroY = $gy;
//                    $sensor->GyroZ = $gz;
//                    $sensor->save();
//                }
            }
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }



    public function actionCreateforjoe()
    {
        ini_set('max_execution_time', 30000);
        date_default_timezone_set('GMT');
        $data = Yii::$app->request->post();
//        Yii::getLogger()->log(print_r($data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $pure_data = $data['Sensor'];
        $user_id = $data['user_id'];
        $list = array();
        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while(strlen($pure_data) != 0) {

            if($pure_data[0] == "A"){
                $temp_data = substr($pure_data, 1);
                $pos_a = strpos($temp_data, "A");
                $pos_g = strpos($temp_data, "G");
                if ($pos_a == false and $pos_g == false) $row = $temp_data;
                else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                else {
                    if ($pos_a > $pos_g) $pos = $pos_g;
                    else $pos = $pos_a;
                    $row = substr($temp_data, 0, $pos);
                }
                $pos_x = strpos($row, "X");
                $pos_y = strpos($row, "Y");
                $pos_z = strpos($row, "Z");
                $time = substr($row, 1, $pos_x-1);
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));

                // ***********************************************
                // realtime is the "datetime" in table 'sensor'
                $realtime = $yearmonthday . " " . $hoursecond;
                // ***********************************************

                $ax = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                $ay = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                $az = substr($row, $pos_z+1);
                // remove last row
                $pure_data = substr($pure_data, strlen($row)+1);

                // ***********************************************
                // shorttime is the "time" in table 'sensor'
                $shorttime = strtotime($realtime) . substr($time, 5,3);
                // ***********************************************

                // ********************************************************************************
                // see the next "G" row, check if the time is the same as the current "A" row
                // ********************************************************************************
                if ($pure_data[0] == "G") {
                    $pos_x = strpos($pure_data, "X");
                    // new_time is the time of next "G" row
                    $new_time = substr($pure_data, 2, $pos_x - 2);
                    // if the new_time of "G" row = time of "A" row, we need to store it into one row!!!!!!!!!!
                    if ($new_time == $time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");

                        $gx = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                        $gy = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                        $gz = substr($row, $pos_z + 1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $model = new Sensor();
                        $model->user_id = $user_id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($az)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
                        $model->save();

                    }
                    else {
                        $model = new Sensor();
                        $model->user_id = $user_id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->time = $shorttime ;
                        $model->save();
                    }
                }
                else {
                    $model = new Sensor();
                    $model->user_id = $user_id;
                    $model->datetime = $realtime;
                    $model->accelX = $ax;
                    $model->accelY = $ay;
                    if (!is_numeric(substr($az,strlen($az)-1,1))) {
                        $az = substr($az, 0, -1);
                    }
                    $model->accelZ = $az;
                    $model->time = $shorttime ;
                    $model->save();
                }


//                $sensor = Sensor::findOne(['time' => $shorttime]);
////                Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//                if (sizeof($sensor) == 0) {
////                    Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model = new Sensor();
//                    $model->user_id = Yii::$app->user->id;
//                    $model->datetime = $realtime;
//                    $model->accelX = $ax;
//                    $model->accelY = $ay;
//                    $model->accelZ = $az;
//                    $model->time = $shorttime ;
////                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model->save();
//                }
//                else {
//                    $sensor->accelX = $ax;
//                    $sensor->accelY = $ay;
//                    $sensor->accelZ = $az;
//                    $sensor->save();
//                }
            }
            else if($pure_data[0] == "G") {
                $temp_data = substr($pure_data, 1);
                $pos_a = strpos($temp_data, "A");
                $pos_g = strpos($temp_data, "G");
                if ($pos_a == false and $pos_g == false) $row = $temp_data;
                else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                else {
                    if ($pos_a > $pos_g) $pos = $pos_g;
                    else $pos = $pos_a;
                    $row = substr($temp_data, 0, $pos);
                }
                $pos_x = strpos($row, "X");
                $pos_y = strpos($row, "Y");
                $pos_z = strpos($row, "Z");
                $time = substr($row, 1, $pos_x - 1);
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;

                $gx = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                $gy = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                $gz = substr($row, $pos_z + 1);
                $pure_data = substr($pure_data, strlen($row) + 1);
                $shorttime = strtotime($realtime) . substr($time, 5,3);

                if ($pure_data[0] == "A") {
                    $pos_x = strpos($pure_data, "X");
                    $new_time = substr($pure_data, 2, $pos_x - 2);
                    if ($new_time == $time) {
                        $temp_data = substr($pure_data, 1);
                        $pos_a = strpos($temp_data, "A");
                        $pos_g = strpos($temp_data, "G");
                        if ($pos_a == false and $pos_g == false) $row = $temp_data;
                        else if ($pos_a == false and $pos_g != false) $row = substr($temp_data, 0, $pos_g);
                        else if ($pos_g == false and $pos_a != false) $row = substr($temp_data, 0, $pos_a);
                        else {
                            if ($pos_a > $pos_g) $pos = $pos_g;
                            else $pos = $pos_a;
                            $row = substr($temp_data, 0, $pos);
                        }
                        $pos_x = strpos($row, "X");
                        $pos_y = strpos($row, "Y");
                        $pos_z = strpos($row, "Z");

                        $ax = substr($row, $pos_x + 1, $pos_y - $pos_x - 1);
                        $ay = substr($row, $pos_y + 1, $pos_z - $pos_y - 1);
                        $az = substr($row, $pos_z + 1);
                        $pure_data = substr($pure_data, strlen($row)+1);
                        $model = new Sensor();
                        $model->user_id = $user_id;
                        $model->datetime = $realtime;
                        $model->accelX = $ax;
                        $model->accelY = $ay;
                        if (!is_numeric(substr($az,strlen($az)-1,1))) {
                            $az = substr($az, 0, -1);
                        }
                        $model->accelZ = $az;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
//                        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                        $model->save();

                    }
                    else {
                        $model = new Sensor();
                        $model->user_id = $user_id;
                        $model->datetime = $realtime;
                        $model->GyroX = $gx;
                        $model->GyroY = $gy;
                        if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                            $gz = substr($gz, 0, -1);
                        }
                        $model->GyroZ = $gz;
                        $model->time = $shorttime ;
                        $model->save();
                    }
                }
                else {
                    $model = new Sensor();
                    $model->user_id = $user_id;
                    $model->datetime = $realtime;
                    $model->GyroX = $gx;
                    $model->GyroY = $gy;
                    if (!is_numeric(substr($gz,strlen($gz)-1,1))) {
                        $gz = substr($gz, 0, -1);
                    }
                    $model->GyroZ = $gz;
                    $model->time = $shorttime ;
                    $model->save();
                }


//                $sensor = Sensor::findOne(['time' => $shorttime]);
////                Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
//                if (sizeof($sensor) == 0) {
////                    Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model = new Sensor();
//                    $model->user_id = Yii::$app->user->id;
//                    $model->datetime = $realtime;
//                    $model->GyroX = $gx;
//                    $model->GyroY = $gy;
//                    $model->GyroZ = $gz;
//                    $model->time = $shorttime ;
////                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                    $model->save();
//                }
//                else {
//                    $sensor->GyroX = $gx;
//                    $sensor->GyroY = $gy;
//                    $sensor->GyroZ = $gz;
//                    $sensor->save();
//                }
            }
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }


    /**
     * Updates an existing sensor model.
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
     * Deletes an existing sensor model.
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
     * Finds the sensor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return sensor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = sensor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public static function bytesToInteger($bytes, $position) {
//        $i = unpack("L",pack("C*",$ar[1],$ar[2],$ar[3],$ar[4]));
        $val = 0;
        $val = $bytes[$position + 3] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 2] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 1] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position] & 0xff;
        return $val;
    }

    public static function bytesToShort($bytes, $position) {
        $val = 0;
        $val = $bytes[$position + 1] & 0xFF;
        $val = $val << 8;
        $val |= $bytes[$position] & 0xFF;
        return $val;
    }

    public static function bytesTo6Long($bytes, $position) {
        $val = 0;
        $val = $bytes[$position + 5] & 0xff;
        $val <<= 8;
        $val = $bytes[$position + 4] & 0xff;
        $val <<= 8;
        $val = $bytes[$position + 3] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 2] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 1] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position] & 0xff;
        return $val;
    }

    public static function bytesTo8Long($bytes, $position) {
        $val = 0;
        $val = $bytes[$position + 7] & 0xff;
        $val <<= 8;
        $val = $bytes[$position + 6] & 0xff;
        $val <<= 8;
        $val = $bytes[$position + 5] & 0xff;
        $val <<= 8;
        $val = $bytes[$position + 4] & 0xff;
        $val <<= 8;
        $val = $bytes[$position + 3] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 2] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 1] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position] & 0xff;
        return $val;
    }

    public static function bytesToChar($bytes, $position) {
        $char = chr($bytes[$position+1]);
        return $char;
    }

    public static function bytesToFloat($bytes, $position) {
        $float = unpack('f', pack('c*', $bytes[$position], $bytes[$position+1], $bytes[$position+2], $bytes[$position+3]));
        $float_str = sprintf('%f', $float[0]);
        return $float_str;
    }

    public function actionTestbytes()
    {
        Yii::getLogger()->log(print_r("data",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $pure_data = file_get_contents('php://input');
        Yii::getLogger()->log(print_r($pure_data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        Yii::getLogger()->log(print_r(gettype($pure_data),true),yii\log\Logger::LEVEL_INFO,'MyLog');

        $length = count($pure_data);
        Yii::getLogger()->log(print_r($length,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current = 0;
        Yii::getLogger()->log(print_r("beginning",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $aorg = bytesToChar($pure_data, $current);
        Yii::getLogger()->log(print_r($aorg,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 2;
        $time = bytesTo6Long($pure_data, $current);
        Yii::getLogger()->log(print_r($aorg,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 6;
        $x = bytesToFloat($pure_data, $current);
        Yii::getLogger()->log(print_r($x,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 4;
        $y = bytesToFloat($pure_data, $current);
        Yii::getLogger()->log(print_r($y,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 4;
        $z = bytesToFloat($pure_data, $current);
        Yii::getLogger()->log(print_r($z,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 4;
        $imei = bytesTo8Long($pure_data, $current);
        Yii::getLogger()->log(print_r($imei,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $current += 8;

        $t = time();
        $yearmonthday = date('Y-m-d',$t);
        $hoursecond = date('H:i:s', substr(sprintf('%.0f', $time), 0, 5));
        $realtime = $yearmonthday . " " . $hoursecond;
        $ax = $x;
        $ay = $y;
        $az = $z;
        $shorttime = strtotime($realtime) . substr(sprintf('%.0f', $time), 5,3);

        $model = new Sensor();
        $model->user_id = Yii::$app->user->id;
        $model->datetime = sprintf('%.0f', $realtime);
        $model->accelX = sprintf('%.0f', $ax);
        $model->accelY = sprintf('%.0f', $ay);
        $model->accelZ = sprintf('%.0f', $az);
        $model->time = $shorttime ;
        Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $model->save();
        Yii::getLogger()->log(print_r("end",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }



    public function actionCreatebytes()
    {
        ini_set('max_execution_time', 30000);
        date_default_timezone_set('GMT');
        $pure_data = file_get_contents('php://input');
        $length = count($pure_data);
        $current = 0;
        Yii::getLogger()->log(print_r("beginning!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while($current != $length) {
            $aorg = bytesToChar($pure_data, $current);
            $current += 2;
            $time = bytesTo6Long($pure_data, $current);
            $current += 6;
            $x = bytesToFloat($pure_data, $current);
            $current += 4;
            $y = bytesToFloat($pure_data, $current);
            $current += 4;
            $z = bytesToFloat($pure_data, $current);
            $current += 4;
            $imei = bytesTo8Long($pure_data, $current);
            $current += 8;
            if($aorg == "A"){
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr(sprintf('%.0f', $time), 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;
                $ax = $x;
                $ay = $y;
                $az = $z;
                $shorttime = strtotime($realtime) . substr(sprintf('%.0f', $time), 5,3);

                if ($current == $length) {
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = sprintf('%.0f', $realtime);
                    $model->accelX = sprintf('%.0f', $ax);
                    $model->accelY = sprintf('%.0f', $ay);
                    $model->accelZ = sprintf('%.0f', $az);
                    $model->time = $shorttime ;
                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                    continue;
                }
                else {
                    $newaorg = bytesToChar($pure_data, $current);
                    $current += 1;
                    $newtime = bytesTo6Long($pure_data, $current);
                    $current += 6;
                    $newx = bytesToFloat($pure_data, $current);
                    $current += 4;
                    $newy = bytesToFloat($pure_data, $current);
                    $current += 4;
                    $newz = bytesToFloat($pure_data, $current);
                    $current += 4;
                    $newimei = bytesTo8Long($pure_data, $current);
                    $current += 8;
                    if ($aorg == "A") {
                        $current -= 28;
                        continue;
                    }
                    else {
                        if ($time == $newtime) {
                            $model = new Sensor();
                            $model->user_id = Yii::$app->user->id;
                            $model->datetime = sprintf('%.0f', $realtime);
                            $model->accelX = sprintf('%.0f', $ax);
                            $model->accelY = sprintf('%.0f', $ay);
                            $model->accelZ = sprintf('%.0f', $az);
                            $model->accelX = sprintf('%.0f', $newx);
                            $model->accelY = sprintf('%.0f', $newy);
                            $model->accelZ = sprintf('%.0f', $newz);
                            $model->time = $shorttime ;
                            Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                            $model->save();
                        }
                        else {
                            $current -= 28;
                            continue;
                        }
                    }
                }
            }
            else if($aorg == "G"){
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr(sprintf('%.0f', $time), 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;
                $gx = $x;
                $gy = $y;
                $gz = $z;
                $shorttime = strtotime($realtime) . substr(sprintf('%.0f', $time), 5,3);

                if ($current == $length) {
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = sprintf('%.0f', $realtime);
                    $model->accelX = sprintf('%.0f', $gx);
                    $model->accelY = sprintf('%.0f', $gy);
                    $model->accelZ = sprintf('%.0f', $gz);
                    $model->time = $shorttime ;
                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                    continue;
                }
                else {
                    $newaorg = bytesToChar($pure_data, $current);
                    $current += 1;
                    $newtime = bytesTo6Long($pure_data, $current);
                    $current += 6;
                    $newx = bytesToFloat($pure_data, $current);
                    $current += 4;
                    $newy = bytesToFloat($pure_data, $current);
                    $current += 4;
                    $newz = bytesToFloat($pure_data, $current);
                    $current += 4;
                    $newimei = bytesTo8Long($pure_data, $current);
                    $current += 8;
                    if ($aorg == "G") {
                        $current -= 28;
                        continue;
                    }
                    else {
                        if ($time == $newtime) {
                            $model = new Sensor();
                            $model->user_id = Yii::$app->user->id;
                            $model->datetime = sprintf('%.0f', $realtime);
                            $model->accelX = sprintf('%.0f', $newx);
                            $model->accelY = sprintf('%.0f', $newy);
                            $model->accelZ = sprintf('%.0f', $newz);
                            $model->accelX = sprintf('%.0f', $gx);
                            $model->accelY = sprintf('%.0f', $gy);
                            $model->accelZ = sprintf('%.0f', $gz);
                            $model->time = $shorttime ;
                            Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                            $model->save();
                        }
                        else {
                            $current -= 28;
                            continue;
                        }
                    }
                }
            }
        }
        Yii::getLogger()->log(print_r("end!!!!!!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
    }
}
