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
        <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>{first} {last}</strong> device setting', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>
    <div class="panel-body">
        <p>
            <?php echo Yii::t('UserModule.views_account_editDevice', 'If {first} {last} changed device, please connect it here', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
        </p>

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?php if($user->device_id != null) : ?>
                <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>Current device ID :</strong>'); ?>
                <div style="margin: 0 20px">
                    <?php echo CHtml::encode($user->device_id) ?>
<!--                    --><?php //echo Html::a(Yii::t('UserModule.views_account_editDevice', 'Delete'), Url::toRoute(['/user/account/delete-device', 'id' => $model->deviceId]), array('class' => 'btn btn-danger btn-xs pull-right')); ?>

                </div>
            <?php endif; ?>
        </div>
<!--        <hr>-->
<!---->
<!--        --><?php //echo $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45]); ?>


        <?php echo $form->field($model, 'deviceId')->textInput(['maxlength' => 45]); ?>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('UserModule.views_account_editDevice', 'Save'), array('class' => 'btn btn-primary')); ?>

        <!-- show flash message after saving -->
        <?php echo \humhub\widgets\DataSaved::widget(); ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>