<?php
/**
 * User: wufei
 * Date: 4/4/2016
 * Time: 2:26 PM
 */

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\space\modules\manage\widgets\DeviceMenu;
use humhub\modules\space\modules\manage\widgets\AddCareMenu;
?>

<?= DeviceMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver_add', '<strong>Add</strong> Care Receiver'); ?>

    </div>
    <div class="panel-body">
        <?= AddCareMenu::widget(['space' => $space]); ?>
        <p/>
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_add', 'You can create a new account for a <b>care receiver</b> here. '); ?><br>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_add', 'He/she can use this account to <b>log in</b> this web and <b>activate</b> his/her Cosmos.'); ?><br>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_add', '* is required.'); ?>
        </p>
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>

    </div>
</div>