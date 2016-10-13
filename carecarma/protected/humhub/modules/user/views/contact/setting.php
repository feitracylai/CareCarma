<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/12/2016
 * Time: 10:07 AM
 */

use yii\widgets\ActiveForm;
use humhub\compat\CHtml;
use humhub\modules\user\models\User;

?>

<div class="panel panel-default">

    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_setting', '<strong>Privacy</strong> Settings'); ?>
    </div>


    <div class="panel-body">
        <?=\humhub\modules\user\widgets\ContactMenu::widget(); ?>
        <p />
        <hr>

        <p>
            <?php echo Yii::t('UserModule.views_contact_setting', "When you are added in other's contact lists, some of them will need your verification."); ?>
        </p>

        <?php $form = ActiveForm::begin(); ?>
        <?php echo $form->field($model, 'contact_notify_setting')->dropdownList([
            User::CONTACT_NOTIFY_EVERYONE => Yii::t('UserModule.views_contact_setting', 'Everyone'),
            User::CONTACT_NOTIFY_NOCIRCLE => Yii::t('UserModule.views_contact_setting', 'Only person not in my circles'),
            User::CONTACT_NOTIFY_NOONE => Yii::t('UserModule.views_contact_setting', 'No one')]); ?>

        <hr>

        <?php echo CHtml::submitButton(Yii::t('UserModule.views_contact_setting', 'Save'), array('class' => 'btn btn-primary')); ?>

        <!-- show flash message after saving -->
        <?php echo \humhub\widgets\DataSaved::widget(); ?>

        <?php ActiveForm::end(); ?>
    </div>

</div>




