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
        <?php echo Yii::t('UserModule.views_account_delete', '<strong>Delete</strong> {first} {last}', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>

    <div class="panel-body">
        <?php if ($isSpaceOwner) { ?>

            <?php echo Yii::t('UserModule.views_account_delete', 'Sorry, as an owner of a workspace you are not able to delete <u>{first} {last}</u> account!<br />Please assign another owner or delete them.', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>

        <?php } else { ?>

            <?php echo Yii::t('UserModule.views_account_delete', 'Are you sure, that you want to delete <u>{first} {last}</u> account?<br />All his/her published content will be removed! ', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>

            <?php $form = CActiveForm::begin(); ?>

            <p class="help-block">Fields with <span class="required">*</span> are required.</p><br>

            <?php echo $form->errorSummary($model); ?>
            <div class="form-group">
                <?php echo $form->passwordField($model, 'currentPassword', array('class' => 'form-control', 'placeholder' => Yii::t('UserModule.views_account_delete', 'Enter his/her password to delete account'), 'maxlength' => 45)); ?>
            </div>
            <?php echo CHtml::submitButton(Yii::t('UserModule.views_account_delete', 'Delete account'), array('class' => 'btn btn-danger')); ?>


            <!-- show flash message after saving -->
            <?php echo \humhub\widgets\DataSaved::widget(); ?>
            <?php echo Html::a(Yii::t('SpaceModule.views_admin_members', 'Remove to regular member'), $space->createUrl('remove', ['userGuid' => $user->guid]), ['class' => 'btn btn-primary', 'data-method' => 'POST', 'data-confirm' => 'Are you sure? This person will become a general member in this space.']); ?>


            <?php CActiveForm::end(); ?>
        <?php } ?>
    </div>
</div>
