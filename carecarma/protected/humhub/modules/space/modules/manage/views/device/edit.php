<?php
/**
 * User: wufei
 * Date: 4/6/2016
 * Time: 5:16 PM
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
        <?php echo Yii::t('SpaceModule.views_admin_receiver_edit', '<strong>Change</strong> E-mail'); ?>
    </div>
    <div class="panel-body">
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_edit', '{first} {last} can use this e-mail to login, and receive the missed messages. Please make sure this email address is valid.', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
        </p>
        <?php $form = ActiveForm::begin(); ?>

<!--        <div class="form-group">-->
<!--            --><?php //echo Yii::t('SpaceModule.views_admin_receiver_edit', '<strong>Username:</strong>'); ?>
<!--            --><?php //echo CHtml::encode($user->username) ?>
<!--            <hr>-->
            <?php echo Yii::t('SpaceModule.views_admin_receiver_edit', '<strong>Current E-mail address</strong>'); ?><br/>
            <?php echo CHtml::encode($user->email) ?>
            <br/>
        <hr/>
        <?php echo $form->field($emailModel, 'newEmail')->textInput(['maxlength' => 45]); ?>
        <?php echo CHtml::submitButton(Yii::t('SpaceModule.views_admin_receiver_edit', 'Save'), array('class' => 'btn btn-primary')); ?>


        <!-- show flash message after saving -->
        <?php echo \humhub\widgets\DataSaved::widget(); ?>

        <?php ActiveForm::end(); ?>
    </div>



</div>