<?php


use humhub\modules\devices\widgets\ProfileReportMenu;
use humhub\widgets\GoogleChart;

?>

<?= ProfileReportMenu::widget(['user' => $user]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('DevicesModule.views_view_index', '<strong>Steps</strong> Report'); ?>
    </div>
    <div class="panel-body">
        <p style="font-size: 30px">
            <?php echo Yii::t('DevicesModule.views_view_index', 'Yesterday: {yesterday} steps', array('{yesterday}' => $yesterdayStep))?>
        </p>

        <?php
        echo "<hr>";
        for ($count = 0; $count < count($devices); $count++){
            echo Yii::t('DevicesModule.views_view_index', '<strong>{device_type}: {device_name}</strong>', array('{device_type}' => $devices[$count]->type, '{device_name}' => $devices[$count]->model));
            echo GoogleChart::widget(array(
                'visualization' => 'ColumnChart',
                'packages' => '"corechart"',
                'data' => $data[$count],

                'options' => array(
                    'height' => 600,
                    'vAxis' => [
                        'title' => 'steps',
                        'viewWindow' => [
                            'min' => 0
                        ]
                    ],
                    'seriesType' => 'bars',
                    'isStacked' => true,
                    'annotations' => [
                        'alwaysOutside' => true,
                    ]
                )
            ));
        }


        ?>
    </div>
</div>