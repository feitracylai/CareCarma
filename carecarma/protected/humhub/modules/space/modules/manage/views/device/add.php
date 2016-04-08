<?php
/**
 * User: wufei
 * Date: 4/4/2016
 * Time: 2:26 PM
 */

use yii\helpers\Url;
use humhub\modules\space\modules\manage\widgets\DeviceMenu;
?>

<?= DeviceMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver', '<strong>Add</strong> Care Receiver'); ?>
    </div>
    <div class="panel-body">

        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>

    </div>
</div>