<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 2/2/2017
 * Time: 3:26 PM
 */

use humhub\modules\devices\widgets\ProfileReportMenu;
use humhub\widgets\GoogleChart;

?>

<?= ProfileReportMenu::widget(['user' => $user]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('DevicesModule.views_view_index', '<strong>Average</strong> Heart Rate'); ?>
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
                    'hAxis' => array(
                        'format' => 'MMM dd',
                        'gridlines' => array(
                            'color' => 'none',

                        ),
                    )
                )
            ));
        }


        ?>





    </div>

</div>
