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
        <?php echo Yii::t('SpaceModule.views_admin_receiver', '<strong>{first} {last}</strong> ', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>
    <div class="panel-body">

        <p style="font-size: 30px">
            <?php echo Yii::t('SpaceModule.views_admin_receiver', 'Yesterday: {yesterday} steps', array('{yesterday}' => $yesterdayStep))?>
        </p>

        <?php
        echo "<hr>";
        for ($count = 0; $count < count($devices); $count++){
            echo GoogleChart::widget(array(
                'visualization' => 'ColumnChart',
                'packages' => '"corechart"',
                'data' => $data[$count],

                'options' => array(
                'height' => 600,
                'vAxis' => ['title' => 'steps'],
                'seriesType' => 'bars',
                'isStacked' => true,
//                    'annotations' => [
//                        'alwaysOutside' => true,
//                    ]
                )
            ));
        }


        ?>





    </div>


</div>

