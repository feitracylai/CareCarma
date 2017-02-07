<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 2/2/2017
 * Time: 12:00 PM
 */

namespace humhub\modules\devices\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\devices\models\Classlabelshoursteps;
use humhub\modules\devices\models\Classlabelshourheart;
use humhub\modules\user\models\Device;
use Yii;
use yii\log\Logger;

class ViewController extends ContentContainerController
{

    public $hideSidebar = true;

    public function actionIndex()
    {
        $user = $this->contentContainer;

        $dataDevices = Device::find()->where(['user_id' => $user->id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->all();
        if (!$dataDevices){
            return $this->render('nodevices', array(
                'user' => $user,
            ));
        }

        $today = date("Y-m-d");
        date_default_timezone_set("GMT");
        $unixtoday = strtotime($today);
        $unixlastweek = strtotime('-1 week', $unixtoday);
        $start = $unixlastweek."000";
        $end = $unixtoday. "000";

        $basicData = array_fill(0, 7, array_fill(0, 8, 0));
        $basicData0 = ['Month', '0:00 -- 4:00', '4:00 -- 8:00', '8:00 -- 12:00', '12:00 -- 16:00', '16:00 -- 20:00', '20:00 -- 24:00', ['role' => 'annotation']];

        array_unshift($basicData, $basicData0);

        $time = $unixlastweek;
        for ($i = 1; $i < 8; $i++){
            $basicData[$i][0] = date('M d', $time);
            $time = $time + 86400;
        }


        $DATA = array();
        $devices = array();
        $yesterday_step = 0;
        $count = 0;
        foreach ($dataDevices as $dataDevice){
            $deviceReportData = $basicData;
            $steps_data = Classlabelshoursteps::find()->where(['hardware_id' => $dataDevice->hardware_id])
                ->andWhere(['>=', 'time', $start])->andWhere(['<', 'time', $end])->all();
            if ($steps_data){
                foreach ($steps_data as $hourlyrow){
                    $hourlystep = $hourlyrow->stepsLabel;
                    $hourlytime = substr($hourlyrow->time, 0, 10) + 1; //division will have remainder

                    $intervaltime = $hourlytime - $unixlastweek;
                    $row = (int)($intervaltime/86400) + 1; //which day
                    $remainder = $intervaltime - ($row - 1) * 86400;
                    $column = (int)($remainder/14400) + 1; //which hour section

                    $deviceReportData[$row][$column] = $deviceReportData[$row][$column] + $hourlystep;
                    $deviceReportData[$row][7] = $deviceReportData[$row][7] + $hourlystep;


                }
            }

            $yesterday_step = $yesterday_step + $deviceReportData[7][7];
            $DATA[$count] = $deviceReportData;
            $devices[$count] = $dataDevice;
            $count++;

            $lastData = Classlabelshoursteps::find()->where(['hardware_id' => $dataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
            $lastData->seen = 1;
            $lastData->save();
        }

        return $this->render('index', array(
            'user' => $this->contentContainer,
            'data' => $DATA,
            'devices' => $devices,
            'yesterdayStep' => $yesterday_step,
        ));
    }

    public function actionHeartrate()
    {

        $user = $this->contentContainer;
        $dataDevices = Device::find()->where(['user_id' => $user->id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->all();
        if (!$dataDevices){
            return $this->render('nodevices', array(
                'user' => $user,
            ));
        }

        $today = date("Y-m-d");
        date_default_timezone_set("GMT");
        $unixtoday = strtotime($today);
        $unixlastweek = strtotime('-1 week', $unixtoday);
        $start = $unixlastweek*1000;
        $end = $unixtoday*1000;

        $basicData = array_fill(0, 168, array_fill(0, 2, 0));

        $time = $start;
        for ($i = 0; $i < 168; $i++){
            $basicData[$i][0] = $time;
            $time = $time + 3600000;
        }

        $DATA = array();
        $devices = array(); //use to give device details
        $count = 0;
        foreach ($dataDevices as $dataDevice) {
            $deviceReportData = $basicData;
            $heartrate_data = Classlabelshourheart::find()->where(['hardware_id' => $dataDevice->hardware_id])
                ->andWhere(['>=', 'time', $start])->andWhere(['<', 'time', $end])->all();
            if ($heartrate_data){
                foreach ($heartrate_data as $rowData){
                    $hourlyheartrate = $rowData->heartrateLabel;
                    $hourlytime = substr($rowData->time, 0, 10); //division will have remainder

                    $intervaltime = $hourlytime - $unixlastweek;
                    $row = (int)($intervaltime/3600); //which hour

                    $deviceReportData[$row][1] = $hourlyheartrate;
                }

            }
            $DATA[$count] = $deviceReportData;
            $devices[$count] = $dataDevice;
            $count++;

            $lastData = Classlabelshourheart::find()->where(['hardware_id' => $dataDevice->hardware_id])->andWhere(['>=', 'time', $start])->orderBy('updated_at DESC')->one();
            $lastData->seen = 1;
            $lastData->save();
        }
        return $this->render('heartrate', array(
            'user' => $user,
            'data' => $DATA,
            'devices' => $devices,
        ));
    }
}