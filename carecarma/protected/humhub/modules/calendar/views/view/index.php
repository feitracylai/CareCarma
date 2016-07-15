<div class="panel panel-default">
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