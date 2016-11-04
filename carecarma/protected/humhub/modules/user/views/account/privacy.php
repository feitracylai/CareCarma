<?php

use yii\widgets\ActiveForm;
use humhub\modules\user\models\User;
use humhub\compat\CHtml;

?>

<div class="panel-heading">
    <?php echo Yii::t('UserModule.views_account_privacy', '<strong>Privacy</strong> settings'); ?>
</div>

<div class="panel-body">
    <?php $form = ActiveForm::begin(); ?>

    <strong><?php echo Yii::t('UserModule.views_account_privacy', 'Show profile info'); ?></strong><br>
    <p>
        <?php echo Yii::t('UserModule.views_account_privacy', "In your profile, some of them can see your About page, which show your profile info."); ?>
    </p>


    <?php echo $form->field($model, 'view_about_page')->dropdownList([
        User::ABOUT_PAGE_PRIVATE => Yii::t('UserModule.views_account_privacy', 'Your circle members & Your People'),
        User::ABOUT_PAGE_PUBLIC => Yii::t('UserModule.views_account_privacy', 'Only person not in my circles'),
        ]); ?>

    <hr>


    <strong><?php echo Yii::t('UserModule.views_account_privacy', 'People verification'); ?></strong><br>
    <p>
        <?php echo Yii::t('UserModule.views_account_privacy', "When you are added in other's People lists, some of them will need you to accept."); ?>
    </p>


    <?php echo $form->field($model, 'contact_notify_setting')->dropdownList([
        User::CONTACT_NOTIFY_EVERYONE => Yii::t('UserModule.views_account_privacy', 'Everyone '),
        User::CONTACT_NOTIFY_NOCIRCLE => Yii::t('UserModule.views_account_privacy', 'Only person not in my circles'),
        User::CONTACT_NOTIFY_NOONE => Yii::t('UserModule.views_account_privacy', 'No one')]); ?>

    <hr>

    <?php echo CHtml::submitButton(Yii::t('UserModule.views_account_privacy', 'Save'), array('class' => 'btn btn-primary')); ?>

    <!-- show flash message after saving -->
    <?php echo \humhub\widgets\DataSaved::widget(); ?>

    <?php ActiveForm::end(); ?>

</div>