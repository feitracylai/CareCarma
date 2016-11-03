<?php

use yii\widgets\ActiveForm;
use \humhub\compat\CHtml;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_account_deleteDevice', '<strong>Deactivate </strong>your Cosmos'); ?>
    </div>
    <div class="panel-body">
        <p>
            <?php echo Yii::t('UserModule.views_account_deleteDevice', 'Are you sure you want to deactivate your Cosmos <strong>{device Id}</strong>?', array('{device Id}' => CHtml::encode(Yii::$app->user->getIdentity()->device_id))); ?>
        </p>
        <br>
        <?php $form = ActiveForm::begin(); ?>
            <?php echo $form->field($model, 'currentPassword')->passwordInput(['maxlength' => 45]); ?>
            <?php echo Html::a(Yii::t('UserModule.views_account_deleteDevice', 'Deactivate Cosmos'), Url::toRoute(['/user/account/delete-device', 'id' => $user->device_id, 'doit' => 2]), array('class' => 'btn btn-danger', 'data-method' => 'POST')); ?>
            &nbsp;
            <?php echo Html::a(Yii::t('UserModule.views_account_deleteDevice', 'Back'), Url::toRoute(['/user/account/edit-device', 'id' => $user->device_id]), array('class' => 'btn btn-primary')); ?>
        <?php ActiveForm::end(); ?>



    </div>
</div>
