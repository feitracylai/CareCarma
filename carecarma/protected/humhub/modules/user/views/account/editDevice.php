<?php

use yii\widgets\ActiveForm;
use \humhub\compat\CHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\user\models\Device;
?>

<div class="panel-heading">
    <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>Device</strong> setting'); ?>
</div>
<div class="panel-body">
    <p>
        <?php echo Yii::t('UserModule.views_account_editDevice', 'If you have bought a device, please connect it to your account'); ?>
    </p>

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?php if(Yii::$app->user->getIdentity()->device_id != null) : ?>
        <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>Current device</strong>'); ?>
            <div style="margin: 0 20px">
                <p>
                    ID:
                    <?php echo CHtml::encode(Yii::$app->user->getIdentity()->device_id) ?>
                    <br>
                    Phone #:
                    <?php echo CHtml::encode(Device::findOne(['device_id' => Yii::$app->user->getIdentity()->device_id])->phone ) ?>
                    <?php echo Html::a(Yii::t('UserModule.views_account_editDevice', 'Delete'), Url::toRoute(['/user/account/delete-device', 'id' => $model->deviceId]), array('class' => 'btn btn-danger btn-xs pull-right')); ?>

                </p>


            </div>
        <?php endif; ?>
    </div>
    <hr>

    <?php echo $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45]); ?>


    <?php echo $form->field($model, 'deviceId')->textInput(['maxlength' => 45]); ?>

    <hr>
    <?php echo CHtml::submitButton(Yii::t('UserModule.views_account_editDevice', 'Save'), array('class' => 'btn btn-primary')); ?>

    <!-- show flash message after saving -->
    <?php echo \humhub\widgets\DataSaved::widget(); ?>

    <?php ActiveForm::end(); ?>
</div>
