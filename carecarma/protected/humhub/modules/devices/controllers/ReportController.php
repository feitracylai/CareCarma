<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/13/2017
 * Time: 4:52 PM
 */

namespace humhub\modules\devices\controllers;

use humhub\modules\space\models\Membership;
use Yii;
use humhub\modules\user\models\User;
use humhub\components\Controller;

class ReportController extends Controller
{


    public function actionReportList()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $space_list = array();
        $memberships = Membership::findAll(['user_id' => $user->id]);
        foreach ($memberships as $membership){

           $space_list[] = $membership->space_id;
        }



        return $this->renderAjax('reportList', array('space_list' => $space_list));
    }
}