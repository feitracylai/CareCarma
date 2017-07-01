
<?php if (count($device_shows) != 0) : ?>
    <?php foreach ($device_shows as $device_show) : ?>
        <?php echo $this->render('_receiverPreview', array('device_show' => $device_show)); ?>
    <?php endforeach; ?>
<?php else: ?>
    <li class="placeholder"> <?php echo Yii::t('DevicesModule.views_report_list', 'There are no health reports you can see.'); ?></li>
<?php endif; ?>
