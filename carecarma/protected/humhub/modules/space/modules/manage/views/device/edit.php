<?php
/**
 * User: wufei
 * Date: 4/6/2016
 * Time: 5:16 PM
 */

use humhub\compat\CActiveForm;
use humhub\compat\CHtml;
use humhub\models\Setting;
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\GridView;
use humhub\modules\space\modules\manage\widgets\DeviceMenu;
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver_edit', '<strong>Edit</strong> Care Receiver account'); ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php $form = \yii\widgets\ActiveForm::begin(); ?>
            <?php echo $hForm->render($form); ?>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>

    </div>
</div>