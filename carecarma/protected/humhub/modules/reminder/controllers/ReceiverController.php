<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/24/2017
 * Time: 10:47 AM
 */

namespace humhub\modules\reminder\controllers;

use humhub\models\MultipleModel;
use humhub\modules\reminder\models\ReminderDevice;
use humhub\modules\reminder\models\ReminderDeviceTime;
use humhub\modules\user\models\Device;
use humhub\modules\user\models\User;
use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\reminder\models\ReminderDeviceSearch;
use humhub\compat\HForm;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use yii\web\HttpException;


class ReceiverController extends ContentContainerController
{

    public $hideSidebar = true;
    public function actionIndex()
    {

        $space = $this->contentContainer;
        $receiver = User::findOne(['guid' => Yii::$app->request->get('rguid')]);

        $searchModel = new ReminderDeviceSearch();
//        $searchModel->user_id = $receiver->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $receiver->id);

//        Yii::getLogger()->log($dataProvider->count, Logger::LEVEL_INFO, 'MyLog');

        return $this->render('index', array(
            'space' => $space,
            'dataProvider' => $dataProvider,
            'receiver' => $receiver,
        ));
    }


    public function actionAdd()
    {
        $space = $this->contentContainer;
        $receiver =User::findOne(['guid' => Yii::$app->request->get('rguid')]);


        $reminder = new ReminderDevice();
        $reminder->user_id = $receiver->id;
        $reminder->update_user_id = Yii::$app->user->id;

        $reminder_times = [new ReminderDeviceTime()];

//        $reminder_time = new ReminderDeviceTime();

        if ($reminder->load(Yii::$app->request->post())) {

            $reminder_times = MultipleModel::createMultiple(ReminderDeviceTime::className());
            MultipleModel::loadMultiple($reminder_times, Yii::$app->request->post());
//            Yii::getLogger()->log(Yii::$app->request->post(), Logger::LEVEL_INFO, 'MyLog');
            //validate all models
            if ($reminder->validate() && MultipleModel::validateMultiple($reminder_times)) {
//                Yii::getLogger()->log('add', Logger::LEVEL_INFO, 'MyLog');


                if ($flag = $reminder->save(false)) {
                    foreach ($reminder_times as $reminder_time) {
//                        Yii::getLogger()->log($reminder_time->repeat, Logger::LEVEL_INFO, 'MyLog');

                        $reminder_time->reminder_id = $reminder->id;
                        if (!($flag = $reminder_time->save(false))){
                            break;
                        }
                    }


                }
                if ($flag){
                    return $this->htmlRedirect($space->createUrl('index', ['rguid' => $receiver->guid]));
                }
            }

        }


        return $this->renderAjax('add', array(
            'reminder' => $reminder,
//            'reminder_time' => $reminder_time,
            'reminder_times' => (empty($reminder_times))? [new ReminderDeviceTime()]:$reminder_times,
            'space' => $space,
            'receiver' => $receiver,
        ));
    }


    public function actionEdit()
    {
        $space = $this->contentContainer;
        $receiver =User::findOne(['guid' => Yii::$app->request->get('rguid')]);
        $id = Yii::$app->request->get('id');

        $reminder = $this->findModel($id);
        $reminder_times = $reminder->times;

        if ($reminder->load(Yii::$app->request->post())){
            $oldIDs = ArrayHelper::map($reminder_times, 'id', 'id');
            $reminder_times = MultipleModel::createMultiple(ReminderDeviceTime::className(), $reminder_times);
            MultipleModel::loadMultiple($reminder_times, Yii::$app->request->post());
            $deleteIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($reminder_times, 'id', 'id')));
//            Yii::getLogger()->log($deleteIDs, Logger::LEVEL_INFO, 'MyLog');
            //validate all models
            if ($reminder->validate() && MultipleModel::validateMultiple($reminder_times)){
//                Yii::getLogger()->log(print_r($reminder_times, 'true'), Logger::LEVEL_INFO, 'MyLog');
                if ($flag = $reminder->save(false)) {
                    if (!empty($deleteIDs)){
                        ReminderDeviceTime::deleteAll(['id' => $deleteIDs]);
                    }
                    foreach ($reminder_times as $reminder_time) {
//                        Yii::getLogger()->log($reminder_time->repeat, Logger::LEVEL_INFO, 'MyLog');

                        $reminder_time->reminder_id = $reminder->id;
                        if (!($flag = $reminder_time->save(false))){
                            break;
                        }
                    }


                }

                if ($flag){
                    return $this->htmlRedirect($space->createUrl('index', ['rguid' => $receiver->guid]));
                }
            }


        }

        return $this->renderAjax('add', array(
            'reminder' => $reminder,
            'reminder_times' => $reminder_times,
            'space' => $space,
            'receiver' => $receiver
        ));
    }

    public function actionDelete()
    {
        $id = (int) Yii::$app->request->get('id');
//        Yii::getLogger()->log($id, Logger::LEVEL_INFO, 'MyLog');

        if ($id != 0) {
            $reminder = ReminderDevice::findOne(['id' => $id]);

            if ($reminder) {
                $reminder->delete();
            }
        }



//        return $this->renderAjax('delete');
        Yii::$app->response->format='json';
        return ['status'=>'ok'];
    }



    protected function findModel($id)
    {
        if (($model = ReminderDevice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }



}