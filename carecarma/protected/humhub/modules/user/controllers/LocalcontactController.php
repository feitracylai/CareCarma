<?php

namespace humhub\modules\user\controllers;

//require '/../vendor/autoload.php';

use Yii;
use humhub\modules\user\models\Localcontact;
use humhub\modules\user\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

/**
 * BeaconController implements the CRUD actions for beacon model.
 */
class LocalcontactController extends Controller
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
     * Creates a new beacon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::getLogger()->log(print_r(Yii::$app->request->post(), true), yii\log\Logger::LEVEL_INFO, 'MyLog');

        $data = Yii::$app->request->post();
        $json_data = $data['contact'];
        $token = $data['token'];
        $contact_list = json_decode($json_data, TRUE);
//        $user = User::findOne(['username' => $username]);


        foreach ($contact_list as $contact) {

            $name = $contact['name'];
            $email = $contact['email'];
            $phone_number1 = $contact['phone_number1'];
            $phone_number2 = $contact['phone_number2'];
            $phone_number3 = $contact['phone_number3'];

            $model = new localcontact();
            $model->user_id = 0;
            $model->token= $token;
            $model->name = $name;
            $model->email = $email;
            $model->phone_number1 = $phone_number1;
            $model->phone_number2 = $phone_number2;
            $model->phone_number3 = $phone_number3;

            Yii::getLogger()->log(print_r($model, true), yii\log\Logger::LEVEL_INFO, 'MyLog');
            $model->save();
        }
        echo 'Success';

//        return $this->render('localcontact', array(
//            'thisUser' => $user
//        ));
    }

}
