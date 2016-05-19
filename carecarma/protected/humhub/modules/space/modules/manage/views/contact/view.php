<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('UserModule.views_contact_delete', 'Contact: <strong>{contact_first} {contact_last}</strong>', array('{contact_first}' => $model->contact_first, '{contact_last}' => $model->contact_last)); ?></div>
    <div class="panel-body">


        <?php
        echo \yii\widgets\DetailView::widget([
            'model' => $model,
            'attributes' => [
                'contact_first',
                'contact_last',
                'contact_mobile',
                'contact_email:email',
                'nickname',

            ],
        ]);
        ?>

        <br/>
        <?php echo Html::a(Yii::t('UserModule.views_contact_delete', 'Back'), Url::toRoute(['/space/manage/contact', 'Cid' => $model->contact_id, 'id' => $model->user_id, 'sguid' => $space->guid]), array('class' => 'btn btn-primary')); ?>


    </div>
</div>