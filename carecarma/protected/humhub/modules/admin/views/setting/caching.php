<?php

use humhub\compat\CActiveForm;
use humhub\compat\CHtml;
use humhub\models\Setting;
use yii\helpers\Url;
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('AdminModule.views_setting_caching', '<strong>Cache</strong> Settings'); ?></div>
    <div class="panel-body">


        <?php $form = CActiveForm::begin(); ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'type'); ?>
            <?php echo $form->dropDownList($model, 'type', $cacheTypes, array('class' => 'form-control', 'readonly' => Setting::IsFixed('type', 'cache'))); ?>
            <br>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'expireTime'); ?>
            <?php echo $form->textField($model, 'expireTime', array('class' => 'form-control', 'readonly' => Setting::IsFixed('expireTime', 'cache'))); ?>
            <br>
        </div>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('AdminModule.views_setting_caching', 'Save & Flush Caches'), array('class' => 'btn btn-primary')); ?>

        <?php echo \humhub\widgets\DataSaved::widget(); ?>
        <?php CActiveForm::end(); ?>

    </div>
</div>






