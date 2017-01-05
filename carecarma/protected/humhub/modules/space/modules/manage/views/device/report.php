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
            <?php echo Yii::t('SpaceModule.views_admin_receiver', 'Yesterday: 3304 steps')?>
        </p>
        <hr>
<!--        --><?php
//            echo GoogleChart::widget(array(
//                'visualization' => 'ColumnChart',
//                'packages' => '"corechart", "bar"',
//                'data' => $data,
//
//
//                'options' => array(
//                    'width' => 600,
//                    'height' => 400,
//                    'legend' => array(
//                        'position' => 'top',
//                        'maxLine' => 3
//                    ),
//                    'bar' => array(
//                        'groupWidth' => '75%',
//                    ),
////                    'isStacked' => true
//                )
//            ));
//        ?>

        <?php
        echo GoogleChart::widget(array(
            'visualization' => 'ComboChart',
            'packages' => '"corechart"',
            'data' => [
                ['Month', '0:00 -- 4:00', '4:00 -- 8:00', '8:00 -- 12:00', '12:00 -- 16:00', '16:00 -- 20:00', '20:00 -- 24:00', ['role' => 'annotation']],
                ['Dec 30',  50,      522,         938,             998,           450,      614, 3572],
                ['Dec 31',  35,      599,        1120,             1268,          288,      682, 3992],
                ['Jan 1',  0,      587,        1167,             807,           397,      623, 3581],
                ['Jan 2',  75,      615,        1110,             968,           215,      609, 3592],
                ['Jan 3',  23,      629,         691,             1026,          366,      569, 3304]
            ],


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

        ?>


    </div>
    <div id="chart_div"></div>
    <script type="text/javascript">
//        google.charts.load('current', {packages: ['corechart']});
//        google.charts.setOnLoadCallback(drawBasic);
//
//        function drawBasic() {
//
//            var data = google.visualization.arrayToDataTable([
//                    ['Month', '0:00 -- 4:00', '4:00 -- 8:00', '8:00 -- 12:00', '12:00 -- 16:00', '16:00 -- 20:00', '20:00 -- 24:00', { role: 'annotation' }],
//                ['Dec 30',  50,      522,         938,             998,           450,      614, 3572],
//                ['Dec 31',  35,      599,        1120,             1268,          288,      682, 3992],
//                ['Jan 1',  0,      587,        1167,             807,           397,      623, 3581],
//                ['Jan 2',  75,      615,        1110,             968,           215,      609, 3592],
//                ['Jan 3',  23,      629,         691,             1026,          366,      569, 3304]
//            ]);
//
//            var options = {
//                height: 600,
//                seriesType: 'bars',
//                isStacked: true,
//            };
//
//            var chart = new google.visualization.ColumnChart(
//                document.getElementById('chart_div'));
//
//            chart.draw(data, options);
//        }
    </script>

</div>

