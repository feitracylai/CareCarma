<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('UserModule.views_contact_delete', 'Delete PEOPLE: <strong>{contact_first} {contact_last}</strong>', array('{contact_first}' => $model->contact_first, '{contact_last}' => $model->contact_last)); ?></div>
    <div class="panel-body">


        <p>
            <?php echo Yii::t('AdminModule.views_user_delete', 'Are you sure you want to delete this PEOPLE?'); ?>
        </p>

<!--        --><?php
//        echo \yii\widgets\DetailView::widget([
//            'model' => $model,
//            'attributes' => [
//                'contact_first',
//                'contact_last',
//                'contact_mobile',
//                'contact_email:email',
//                'nickname',
//
//            ],
//        ]);
//        ?>

        <br/>
        <?php echo Html::a(Yii::t('UserModule.views_contact_delete', 'Delete PEOPLE'), $user->createUrl('delete', ['id' => $model->contact_id, 'doit' => 2]), array('class' => 'btn btn-danger', 'data-method' => 'POST')); ?>
        &nbsp;
        <?php echo Html::a(Yii::t('UserModule.views_contact_delete', 'Back'), Url::toRoute('index'), array('class' => 'btn btn-primary')); ?>


    </div>
</div>