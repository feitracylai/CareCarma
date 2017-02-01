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
            echo GoogleChart::widget(array(
                'visualization' => 'LineChart',
                'packages' => '"corechart"',
                'addColumn' => [
                    ['datetime', 'Time of Day'],
                    ['number', 'Average Heart Rate'],
                ],


                'data' => $data[$count],

                'options' => array(
                    'height' => 600,
//                    'hAxis' => array(
//                        'gridlines' => array(
//                            'count' => 0
//                        ),
//                    )
                )
            ));
        }


        ?>





    </div>


</div>

