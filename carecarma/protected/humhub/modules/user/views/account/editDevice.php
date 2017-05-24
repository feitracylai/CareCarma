<?php

use yii\widgets\ActiveForm;
use \humhub\compat\CHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\user\models\Device;
?>

<div class="panel-heading">
    <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>CoSMoS</strong> setting'); ?>
</div>
<div class="panel-body">
    <p>
        <?php echo Yii::t('UserModule.views_account_editDevice', 'If you have a CoSMoS device or use a CoSMoS App, please input your <strong>Activation ID</strong> to activate it here.'); ?>
    </p>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?php if($device_list != null) :
            $count = 1;
            ?>
        <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>Current CoSMoS device</strong>'); ?>
            <?php foreach ($device_list as $device){?>
                    <div style="margin: 0 20px">
                        <p>
                            <?php
                            echo Yii::t('UserModule.views_account_editDevice', '{count}) {model}', array('{count}' => $count, '{model}' => $device->model));
                            $count++;
                            ?>
                            <?php echo Html::a(Yii::t('UserModule.views_account_editDevice', 'Deactivate'), Url::toRoute(['/user/account/delete-device', 'id' => $device->device_id]), array('class' => 'btn btn-danger btn-xs pull-right')); ?>
                            <br>
                            Activation #:
                            <?php echo CHtml::encode($device->device_id) ?>
                            <br>
                            Phone #:
                            <?php echo CHtml::encode($device->phone) ?>
                            <?php if($count != count($device_list)+1){ echo '<hr>'; } ?>
                        </p>


                    </div>
            <?php } ?>
        <?php endif; ?>
    </div>
    <hr>

    <p>
        <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>Acitivate New Deivce</strong>'); ?>
    </p>
    <?php echo $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45]); ?>


    <?php echo $form->field($model, 'deviceId')->textInput(['maxlength' => 45]); ?>

    <hr>
    <?php echo CHtml::submitButton(Yii::t('UserModule.views_account_editDevice', 'Save'), array('class' => 'btn btn-primary')); ?>

    <!-- show flash message after saving -->
    <?php echo \humhub\widgets\DataSaved::widget(); ?>

    <?php ActiveForm::end(); ?>
</div>



