<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
use \humhub\modules\space\modules\manage\widgets\ContactMenu;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver_contact_add', '<strong>Add</strong> contact'); ?>
    </div>


    <div class="panel-body">
        <?=ContactMenu::widget(['space' => $space]); ?>
        <p />
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>



</div>