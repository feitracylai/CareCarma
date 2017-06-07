<?php

use \humhub\compat\CActiveForm;
use \humhub\compat\CHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver_delete', '<strong>Delete</strong> account'); ?>
    </div>

    <div class="panel-body">
        <?php if ($isSpaceOwner) { ?>

            <?php echo Yii::t('SpaceModule.views_admin_receiver_delete', 'Sorry, as an owner of a workspace you are not able to delete <b><u>{first} {last}</u></b> account!<br />Please assign another owner or delete them.', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>

        <?php } else { ?>

            <?php echo Yii::t('SpaceModule.views_admin_receiver_delete', 'Are you sure you want to delete <b><u>{first} {last}</u></b> account?<br />All his/her published content will be removed! ', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>

            <?php $form = CActiveForm::begin(); ?>

            <p class="help-block">Fields with <span class="required">*</span> are required.</p>

            <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
                <?php echo $form->passwordField($model, 'currentPassword', array('class' => 'form-control', 'placeholder' => Yii::t('UserModule.views_account_delete', 'Enter his/her password to delete account'), 'maxlength' => 45)); ?>
            </div>
            <?php echo CHtml::submitButton(Yii::t('SpaceModule.views_admin_receiver_delete', 'Delete account'), array('class' => 'btn btn-danger')); ?>

            <?php CActiveForm::end(); ?>
        <?php } ?>
        <hr>
    </div>

    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver_delete', '<strong>Remove</strong> {first} {last} ', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>

    <div class="panel-body">
        <div>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_delete', 'Are you sure you want to remove <b>{first} {last}</b> to be a regular member in this space? If you do that, {first} {last} will not be shown on the', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
            <?php echo Html::a(Yii::t('SpaceModule.views_admin_receiver_delete', '"<b><i class="fa fa-headphones"></i><u> Care receiver</u></b>" page.'), $space->createUrl('index'), array('target' => '_blank')); ?>
        </div>

        <br>
        <?php echo Html::a(Yii::t('SpaceModule.views_admin_receiver_delete', 'Remove to regular member'), $space->createUrl('remove', ['userGuid' => $user->guid]), ['class' => 'btn btn-primary', 'data-method' => 'POST', 'data-confirm' => 'Are you sure? This person will become a general member in this circle.']); ?>

    </div>

</div>
