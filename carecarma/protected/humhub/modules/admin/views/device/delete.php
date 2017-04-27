<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/15/2016
 * Time: 11:25 AM
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('AdminModule.views_device_delete', 'Delete device'); ?></div>
    <div class="panel-body">

        <?php if($model->activate == 0){ ?>

        <p>
            <?php echo Yii::t('AdminModule.views_device_delete', 'Are you sure you want to delete this device? '); ?>
        </p>


        <br/>
        <?php echo Html::a(Yii::t('AdminModule.views_device_delete', 'Delete device'), Url::toRoute(['/admin/device/delete', 'id' => $model->device_id, 'doit' => 2]), array('class' => 'btn btn-danger', 'data-method' => 'POST')); ?>
        &nbsp;
        <?php echo Html::a(Yii::t('AdminModule.views_device_delete', 'Back'), Url::toRoute(['/admin/device/edit', 'id' => $model->device_id]), array('class' => 'btn btn-primary')); ?>
        <?php }else { ?>
            <p>
                <?php echo Yii::t('AdminModule.views_device_delete', 'Sorry, this device link to an user account. Please disconnect them first.'); ?>
            </p>
            <?php echo Html::a(Yii::t('AdminModule.views_device_delete', 'Back'), Url::toRoute(['/admin/device']), array('class' => 'btn btn-primary')); ?>
        <?php } ?>

    </div>
</div>
