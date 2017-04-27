<?php

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
        <?php echo Yii::t('SpaceModule.views_admin_receiver_deleteDevice', '<strong>Deactivate </strong>your CoSMoS'); ?>
    </div>
    <div class="panel-body">
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_deleteDevice', 'Are you sure to deactivate this CoSMoS device (<strong>{device Id}</strong>)?', array('{device Id}' => $device->device_id)); ?>
        </p>
        <br>
            <?php echo Html::a(Yii::t('SpaceModule.views_admin_receiver_deleteDevice', 'Deactivate CoSMoS'), $space->createUrl('delete-device', ['id' => $device->device_id, 'doit' => 2, 'rguid' => $user->guid]), array('class' => 'btn btn-danger', 'data-method' => 'POST')); ?>
            &nbsp;
            <?php echo Html::a(Yii::t('SpaceModule.views_admin_receiver_deleteDevice', 'Back'), $space->createUrl('device', ['rguid' => $user->guid]), array('class' => 'btn btn-primary')); ?>


    </div>
</div>


