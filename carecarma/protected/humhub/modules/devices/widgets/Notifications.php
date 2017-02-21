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
        $device_shows = DeviceShow::findAll(['user_id' => Yii::$app->user->id, 'seen' => 0]);
        $count = count($device_shows);

        return $this->render('notifications', array(
            'newReportCount' => $count
        ));
    }
}