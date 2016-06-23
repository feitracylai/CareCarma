<?php

namespace humhub\modules\user\controllers;

require '/../vendor/autoload.php';

use Yii;
use humhub\modules\user\models\beacon;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

/**
 * BeaconController implements the CRUD actions for beacon model.
 */
class BeaconController extends Controller
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
     * Lists all beacon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => beacon::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single beacon model.
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
     * Creates a new beacon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::getLogger()->log(print_r(Yii::$app->request->post(),true),yii\log\Logger::LEVEL_INFO,'MyLog');

        $data = Yii::$app->request->post();
        $json_data = $data['Beacon'];
        $beacon_list = json_decode($json_data, TRUE);

        foreach ($beacon_list as $beacon) {

            try {
                $beacon_id = $beacon['beacon_id'];
            } catch (Exception $e) {
                $beacon_id = null;
            }
            if (array_key_exists("distance", $beacon)) {
                $distance = $beacon['distance'];
            } else {
                $distance = null;
            }
            if (array_key_exists("datetime", $beacon)) {
                $datetime = $beacon['datetime'];
            } else {
                $datetime = null;
            }

            $model = new beacon();
            $model->user_id = Yii::$app->user->id;
            $model->beacon_id = $beacon_id;
            $model->distance = $distance;
            $model->datetime = $datetime;
            Yii::getLogger()->log(print_r($model,true),yii\log\Logger::LEVEL_INFO,'MyLog');
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
        $tableName = 'beacon';

        $data = Yii::$app->request->post();
        $json_data = $data['Beacon'];
        $beacon_list = json_decode($json_data, TRUE);
//        Yii::getLogger()->log(print_r($beacon_list,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        foreach ($beacon_list as $beacon) {
//            Yii::getLogger()->log(print_r($beacon,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            $beacon_id = $beacon['beacon_id'];
            $user_id = Yii::$app->user->id;
            if (array_key_exists("distance", $beacon)) {
                $distance = $beacon['distance'];
            } else {
                $distance = null;
            }
            if (array_key_exists("datetime", $beacon)) {
                $datetime = $beacon['datetime'];
            } else {
                $datetime = null;
            }
            $json = json_encode([
                'user_id' => $user_id,
                'beacon_id' => $beacon_id,
                'distance' => $distance,
                'datetime' => $datetime
            ]);
            $params = [
                'TableName' => $tableName,
                'Item' => $marshaler->marshalJson($json)
            ];

            try {
                $result = $dynamodb->putItem($params);
//                Yii::getLogger()->log(print_r($result,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            } catch (DynamoDbException $e) {
                echo "Fail\n";
                break;
            }

        }
    }

    /**
     * Updates an existing beacon model.
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
     * Deletes an existing beacon model.
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
        if (($model = beacon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
