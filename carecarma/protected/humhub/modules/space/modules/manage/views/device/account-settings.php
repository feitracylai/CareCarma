<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/19/2016
 * Time: 11:17 AM
 */

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
        <?php echo Yii::t('SpaceModule.views_admin_receiver_edit', '<strong>{first} {last}</strong> account', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin(); ?>

        <!--        <div class="form-group">-->
        <?php echo Yii::t('SpaceModule.views_admin_receiver_edit', '<strong>Username:</strong>'); ?>
        <?php echo CHtml::encode($user->username) ?>
        <hr>
        <?php echo Yii::t('SpaceModule.views_admin_receiver_edit', '<strong>Current E-mail:</strong>'); ?>
        <?php echo CHtml::encode($user->email) ?>
        <br/><br/>
        <?php echo $form->field($emailModel, 'newEmail')->textInput(['maxlength' => 45]); ?>
        <?php echo CHtml::submitButton(Yii::t('SpaceModule.views_admin_receiver_edit', 'Save'), array('class' => 'btn btn-primary')); ?>


        <!-- show flash message after saving -->
        <?php echo \humhub\widgets\DataSaved::widget(); ?>

        <?php ActiveForm::end(); ?>



    </div>

</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>{first} {last}</strong> Cosmos setting', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>
    <div class="panel-body">
        <p>
            <?php echo Yii::t('UserModule.views_account_editDevice', 'If {first} {last} got a new Cosmos, please activate it here', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
        </p>

        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?php if($user->device_id != null) : ?>
                <?php echo Yii::t('UserModule.views_account_editDevice', '<strong>Current Cosmos</strong>'); ?>
                <div style="margin: 0 20px">
                    Activation #:
                    <?php echo CHtml::encode($user->device_id) ?>
                    <br>
                    Phone #:
                    <?php echo CHtml::encode($user->device->phone) ?>
                </div>
            <?php endif; ?>
        </div>


        <?php echo $form->field($model, 'deviceId')->textInput(['maxlength' => 45]); ?>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('UserModule.views_account_editDevice', 'Save'), array('class' => 'btn btn-primary')); ?>

        <!-- show flash message after saving -->
        <?php echo \humhub\widgets\DataSaved::widget(); ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>


