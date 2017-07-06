<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/11/2017
 * Time: 4:06 PM
 */

namespace humhub\modules\reminder\controllers;


use humhub\components\Controller;
use humhub\modules\user\models\User;
use Yii;

class ShowController extends Controller
{

    public function actionIndex()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);

        $message = 'Call grandma for the shopping list.';
        return $this->renderAjax('index', array(
            'user' => $user,
            'message' => $message,
        ));
    }
}