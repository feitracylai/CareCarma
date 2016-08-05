<?php

namespace humhub\modules\user\controllers;

//require '/../vendor/autoload.php';

use Yii;
use humhub\modules\user\models\Sensor;
use yii\data\ActiveDataProvider;
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

        Yii::getLogger()->log(print_r(Yii::$app->request->post(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
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
        date_default_timezone_set('GMT');
        Yii::getLogger()->log(print_r("Someone use the createnew Action!!!!!!",true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        Yii::getLogger()->log(print_r(Yii::$app->request->post(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $data = Yii::$app->request->post();
        $pure_data = $data['Sensor'];
        $list = array();
//        Yii::getLogger()->log(print_r($pure_data[0],true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        $pure_data = substr($pure_data, 1, -1);
        Yii::getLogger()->log(print_r($pure_data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        while(strlen($pure_data) != 0) {

            if($pure_data[0] == "A"){
                $temp_data = substr($pure_data, 1);
//              Yii::getLogger()->log(print_r($temp_data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
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
//                Yii::getLogger()->log(print_r($row,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                $pos_x = strpos($row, "X");
                $pos_y = strpos($row, "Y");
                $pos_z = strpos($row, "Z");
                $time = substr($row, 1, $pos_x-1);
                $t = time();
                $yearmonthday = date('Y-m-d',$t);
                $hoursecond = date('H:i:s', substr($time, 0, 5));
                $realtime = $yearmonthday . " " . $hoursecond;
                Yii::getLogger()->log(print_r($realtime,true),yii\log\Logger::LEVEL_INFO,'MyLog');


                $ax = substr($row, $pos_x+1, $pos_y-$pos_x-1);
                $ay = substr($row, $pos_y+1, $pos_z-$pos_y-1);
                $az = substr($row, $pos_z+1);
//                Yii::getLogger()->log(print_r($time,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                Yii::getLogger()->log(print_r($ax,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                Yii::getLogger()->log(print_r($ay,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//                Yii::getLogger()->log(print_r($az,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                $pure_data = substr($pure_data, strlen($row)+1);
//                Yii::getLogger()->log(print_r($pure_data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                $shorttime = strtotime($realtime) . substr($time, 5,3);
                $sensor = Sensor::findOne(['time' => $shorttime]);
//                Yii::getLogger()->log(print_r($sensor,true),yii\log\Logger::LEVEL_INFO,'MyLog');

                if (sizeof($sensor) == 0) {
                    Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = $realtime;
                    $model->accelX = $ax;
                    $model->accelY = $ay;
                    $model->accelZ = $az;
                    $model->time = $shorttime ;
                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                }
                else {
                    $sensor->accelX = $ax;
                    $sensor->accelY = $ay;
                    $sensor->accelZ = $az;
                    $sensor->save();
                }
        }
            else if($pure_data[0] == "G") {
                $temp_data = substr($pure_data, 1);
//              Yii::getLogger()->log(print_r($temp_data,true),yii\log\Logger::LEVEL_INFO,'MyLog');
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
//                Yii::getLogger()->log(print_r($time, true), yii\log\Logger::LEVEL_INFO, 'MyLog');
//                Yii::getLogger()->log(print_r($gx, true), yii\log\Logger::LEVEL_INFO, 'MyLog');
//                Yii::getLogger()->log(print_r($gy, true), yii\log\Logger::LEVEL_INFO, 'MyLog');
//                Yii::getLogger()->log(print_r($gz, true), yii\log\Logger::LEVEL_INFO, 'MyLog');
                $pure_data = substr($pure_data, strlen($row) + 1);
//                Yii::getLogger()->log(print_r($pure_data, true), yii\log\Logger::LEVEL_INFO, 'MyLog');
//                $model = new sensor();
//                $model->user_id = Yii::$app->user->id;
//                $model->GyroX = $gx;
//                $model->GyroY = $gy;
//                $model->GyroZ = $gz;
//                $model->time = $time;
//                $model->save();
                $shorttime = strtotime($realtime) . substr($time, 5,3);
                $sensor = Sensor::findOne(['time' => $shorttime]);
                if (sizeof($sensor) == 0) {
                    $model = new Sensor();
                    $model->user_id = Yii::$app->user->id;
                    $model->datetime = $realtime;
                    $model->GyroX = $gx;
                    $model->GyroY = $gy;
                    $model->GyroZ = $gz;
                    $model->time = $shorttime;
                    Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
                    $model->save();
                }
                else {
                    $sensor->GyroX = $gx;
                    $sensor->GyroY = $gy;
                    $sensor->GyroZ = $gz;
                    $sensor->save();
                }
            }
        }
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
}
