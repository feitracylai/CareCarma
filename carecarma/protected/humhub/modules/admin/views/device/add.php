<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/14/2016
 * Time: 3:38 PM
 */

?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('AdminModule.views_device_add', '<strong>Add</strong> device'); ?></div>
    <div class="panel-body">
        <?= \humhub\modules\admin\widgets\DeviceMenu::widget(); ?>
        <p />

        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>

    </div>
</div>
