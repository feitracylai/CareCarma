<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="panel panel-default">
    <div class="panel-body">
<!--        --><?php //echo Html::a(Yii::t('CalendarModule.create_new_event', 'New family event'),Url::to(['/calendar/entry/edit', 'start_datetime' => '2016-07-20', 'end_datetime' => '2016-07-20', 'fullCalendar' => 1,'createFromGlobalCalendar' => 1, 'uguid' => '2b861572-00b5-499a-a560-410e7fcf709e' ]), array('class' => 'btn btn-info pull-right', 'data-target' => '#globalModal')); ?>
        <?php echo Html::a(Yii::t('CalendarModule.create_new_event', 'New family event'),$contentContainer->createUrl('/calendar/entry/edit', array('start_datetime' => date("Y-m-d"), 'end_datetime' => date("Y-m-d"), 'fullCalendar' => '1')), array('class' => 'btn btn-info pull-right', 'data-target' => '#globalModal')); ?>
    </div>
    <div class="panel-body">
        <?php
//        Yii::getLogger()->log(print_r( $contentContainer->createUrl('/calendar/entry/edit', array('start_datetime' => '-start-', 'end_datetime' => '-end-', 'fullCalendar' => '1')),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        echo \humhub\modules\calendar\widgets\FullCalendar::widget(array(
            'canWrite' => $contentContainer->canWrite(),
            'loadUrl' => $contentContainer->createUrl('/calendar/view/load-ajax'),
            'createUrl' => $contentContainer->createUrl('/calendar/entry/edit', array('start_datetime' => '-start-', 'end_datetime' => '-end-', 'fullCalendar' => '1'))
        ));
        ?>

    </div>
</div>