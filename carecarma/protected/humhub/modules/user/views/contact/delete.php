<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('UserModule.views_contact_delete', 'Delete contact: <strong>{contact_first} {contact_last}</strong>', array('{contact_first}' => $model->contact_first, '{contact_last}' => $model->contact_last)); ?></div>
    <div class="panel-body">


        <p>
            <?php echo Yii::t('AdminModule.views_user_delete', 'Are you sure you want to delete this Contact?'); ?>
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
        <?php echo Html::a(Yii::t('UserModule.views_contact_delete', 'Delete Contact'), Url::toRoute(['/user/contact/delete', 'id' => $model->contact_id, 'doit' => 2]), array('class' => 'btn btn-danger', 'data-method' => 'POST')); ?>
        &nbsp;
        <?php echo Html::a(Yii::t('UserModule.views_contact_delete', 'Back'), Url::toRoute(['/user/contact/index', 'id' => $model->contact_id]), array('class' => 'btn btn-primary')); ?>


    </div>
</div>