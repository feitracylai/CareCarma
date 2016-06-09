<?php

namespace humhub\modules\user\controllers;

use Yii;
use humhub\modules\user\models\sensor;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

        $data = Yii::$app->request->post();
        $json_data = $data['Sensor'];
        $sensor_list = json_decode($json_data, TRUE);

        foreach ($sensor_list as $sensor) {
            $accelX = $sensor['accelX'];
            $accelY = $sensor['accelY'];
            $accelZ = $sensor['accelZ'];
            $gyroX = $sensor['GyroX'];
            $gyroY = $sensor['GyroY'];
            $gyroZ = $sensor['GyroZ'];
            $compX = $sensor['CompX'];
            $compY = $sensor['CompY'];
            $compZ = $sensor['CompZ'];
            $datetime = $sensor['datetime'];
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
