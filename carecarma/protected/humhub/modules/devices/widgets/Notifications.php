<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:53 PM
 */

namespace humhub\modules\devices\widgets;

use humhub\components\Widget;
use humhub\modules\devices\models\Classlabelshourheart;
use humhub\modules\devices\models\Classlabelshoursteps;
use humhub\modules\devices\models\DeviceShow;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\Device;
use yii\log\Logger;
use Yii;

class Notifications extends Widget
{

    /**
     * Creates the Wall Widget
     */
    public function run()
    {


        $user_id = Yii::$app->user->id;
        $device_shows = DeviceShow::findAll(['user_id' => $user_id, 'seen' => 0]);
        $report_user_array = [];
        foreach ($device_shows as $device_show){
            $report_user_array[$device_show->id] = $device_show->report_user_id;
        }
//        Yii::getLogger()->log($report_user_array, Logger::LEVEL_INFO, 'MyLog');
        $unique_array = array_unique($report_user_array);
//        Yii::getLogger()->log($unique_array, Logger::LEVEL_INFO, 'MyLog');
        $keys = array_keys($unique_array);
//        Yii::getLogger()->log($keys, Logger::LEVEL_INFO, 'MyLog');
        $unique_device_shows = DeviceShow::findAll($keys);

        $count = count($unique_device_shows);

        return $this->render('notifications', array(
            'newReportCount' => $count
        ));
    }
}