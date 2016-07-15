<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/14/2016
 * Time: 3:38 PM
 */

?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('AdminModule.views_user_edit', '<strong>Edit</strong> device'); ?></div>
    <div class="panel-body">
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>
