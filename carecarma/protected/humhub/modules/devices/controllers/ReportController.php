<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/13/2017
 * Time: 4:52 PM
 */

namespace humhub\modules\devices\controllers;

use humhub\modules\devices\models\Classlabelshourheart;
use humhub\modules\devices\models\Classlabelshoursteps;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\Device;
use Yii;
use humhub\modules\user\models\User;
use humhub\components\Controller;
use yii\log\Logger;

class ReportController extends Controller
{


    public function actionReportList()
    {
        $today = date("Y-m-d");
//        date_default_timezone_set("GMT");
        $unixtoday = strtotime($today);
        $unixlastweek = strtotime('-1 week', $unixtoday);
        $start = $unixlastweek."000";

        return $this->renderAjax('reportList', array('start_time' => $start));
    }

    public function actionGetNewReportCountJson()
    {

        Yii::$app->response->format = 'json';

        $today = date("Y-m-d");
//        date_default_timezone_set("GMT");
        $unixtoday = strtotime($today);
        $unixlastweek = strtotime('-1 week', $unixtoday);
        $start = $unixlastweek."000";

        $count = 0;
        //CR's
        foreach (Membership::GetUserSpaces() as $space) {
            $space_members = Membership::find()->where(['space_id' => $space->id, 'group_id' => Space::USERGROUP_MODERATOR])->andWhere(['<>','user_id', Yii::$app->user->id])->all();
            if (count($space_members) != 0){
                foreach ($space_members as $space_member){
                    $member_user_id = $space_member->user_id;
                    $dataDevices = Device::find()->where(['user_id' => $member_user_id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->all();
                    $hasNew = false;
                    if (count($dataDevices) != 0){
                        foreach ($dataDevices as $dataDevice){
                            $last_steps_row = Classlabelshoursteps::find()->where(['hardware_id' => $dataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
                            $last_heartrate_row = Classlabelshourheart::find()->where(['hardware_id' => $dataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
                            if ($last_heartrate_row != null && $last_heartrate_row->seen == 0){
                                $hasNew = true;
                            } elseif ($last_steps_row != null && $last_steps_row->seen == 0){
                                $hasNew = true;
                            }

                        }
                        if ($hasNew)$count++;
                    }

                }
            }
        }

        //user's
        $userDataDevices = Device::find()->where(['user_id' => Yii::$app->user->id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->all();
        $user_hasNew = false;
        if (count($userDataDevices) != 0){
            foreach ($userDataDevices as $userDataDevice){
                $last_steps_row = Classlabelshoursteps::find()->where(['hardware_id' => $userDataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
                $last_heartrate_row = Classlabelshourheart::find()->where(['hardware_id' => $userDataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
                if ($last_heartrate_row != null && $last_heartrate_row->seen == 0){
                    $user_hasNew = true;
                } elseif ($last_steps_row != null && $last_steps_row->seen == 0){
                    $user_hasNew = true;
                }
            }
            if ($user_hasNew)$count++;
        }

        $json = array();
        $json['newReport'] = $count;

        return $json;
    }
}