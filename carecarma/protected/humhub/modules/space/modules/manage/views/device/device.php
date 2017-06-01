<?php

use yii\widgets\ActiveForm;
use \humhub\compat\CHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver_editDevice', '<strong>CoSMoS</strong> setting'); ?>
    </div>
    <div class="panel-body">
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_editDevice', 'If {first} {last} has a CoSMoS Vue or a CareCarma Watch or use CoSMoS App, please activate it here', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
        </p>

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?php if($device_list != null) :
                $count = 1;
                ?>
                <?php echo Yii::t('SpaceModule.views_admin_receiver_editDevice', '<strong>Current CoSMoS device</strong>'); ?><br>
                <?php foreach ($device_list as $device){?>
                        <div style="margin: 0 20px 10px">
                            <?php
                            if ($device->type == 'CWatch'){
                                echo Yii::t('SpaceModule.views_admin_receiver_editDevice', '{count}) <i>CareCarma Watch</i>', array('{count}' => $count));
                            } elseif ($device->type == 'Glass'){
                                echo Yii::t('SpaceModule.views_admin_receiver_editDevice', '{count}) <i>CoSMoS Vue</i>', array('{count}' => $count));
                            } else {
                                echo Yii::t('SpaceModule.views_admin_receiver_editDevice', '{count}) <i>{model}</i>', array('{count}' => $count, '{model}' => $device->model));
                            }
                            $count++;
                            ?>
                            <?php echo Html::a(Yii::t('SpaceModule.views_admin_receiver_editDevice', 'Deactivate'), $space->createUrl('delete-device', ['id' => $device->device_id, 'rguid' => $user->guid]), array('class' => 'btn btn-danger btn-xs pull-right')); ?>
                            <br>
                            Activation #:
                            <?php echo CHtml::encode($device->device_id) ?>
                            <br>
                            Phone #:
                            <?php echo CHtml::encode($device->phone) ?>
                            <?php if($count != count($device_list)+1){ echo '<hr>'; } ?>
                        </div>
                <?php } ?>
            <?php endif; ?>
        </div>
        <hr>
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_editDevice', '<strong>Acitivate New Deivce</strong>'); ?>
        </p>
        <?php echo $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45]); ?>

        <?php echo $form->field($model, 'deviceId')->textInput(['maxlength' => 45]); ?>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('SpaceModule.views_admin_receiver_editDevice', 'Save'), array('class' => 'btn btn-primary')); ?>

        <!-- show flash message after saving -->
        <?php echo \humhub\widgets\DataSaved::widget(); ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
