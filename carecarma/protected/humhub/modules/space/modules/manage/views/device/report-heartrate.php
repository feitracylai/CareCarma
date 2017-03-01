<?php
/**
 * User: wufei
 * Date: 5/11/2016
 * Time: 1:29 PM
 */

use humhub\modules\space\modules\manage\widgets\DeviceReportMenu;
use humhub\widgets\GoogleChart;
?>

<?= DeviceReportMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver', '<strong>{first} {last}</strong> average heart rate', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>
    <div class="panel-body">



        <?php
        echo "<hr>";
        for ($count = 0; $count < count($devices); $count++){
            echo Yii::t('SpaceModule.views_admin_receiver', '<strong>{device_type}: {device_name}</strong>', array('{device_type}' => $devices[$count]->type, '{device_name}' => $devices[$count]->model));
           echo GoogleChart::widget(array(
               'visualization' => 'ColumnChart',
               'packages' => '"corechart"',
               'data' => $data[$count],

               'options' => array(
                   'height' => 600,
                   'seriesType' => 'bars',
                   'vAxis' => [
                       'viewWindow' => [
                           'min' => 0
                       ]
                   ],
                    'annotations' => [
                        'alwaysOutside' => true,
                    ]


               )
           ));
        }


        ?>





    </div>


</div>

