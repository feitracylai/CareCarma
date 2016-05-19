<?php

use humhub\compat\CActiveForm;
use humhub\compat\CHtml;
use humhub\modules\user\models\Contact;
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\GridView;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
use humhub\modules\space\modules\manage\widgets\ContactMenu;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('SpaceModule.views_admin_receiver_contact', '<strong>{first} {last}</strong> Contacts', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?></div>

    <div class="panel-body">
        <?=ContactMenu::widget(['space' => $space]); ?>
        <br/>
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_contact', 'In this overview you can find and manage
             all contacts of this care receiver.'); ?>
        </p>

        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'contact_first',
                'contact_last',
                'contact_mobile',
                'contact_email:email',
                'nickname',
                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['style' => 'width:80px; min-width:80px;'],
                    'buttons' => [
                        'view' => function($url, $model) use ($space) {
                            return Html::a('<i class="fa fa-eye"></i>', Url::toRoute(['view', 'Cid' => $model->contact_id, 'id' => $model->user_id, 'sguid' => $space->guid]), ['class' => 'btn btn-primary btn-xs tt']);
                        },
                        'update' => function($url, $model) use ($space) {
                            return Html::a('<i class="fa fa-pencil"></i>', Url::toRoute(['edit', 'Cid' => $model->contact_id, 'id' => $model->user_id, 'sguid' => $space->guid]), ['class' => 'btn btn-primary btn-xs tt']);
                        },
                        'delete' => function($url, $model) use ($space) {
                            return Html::a('<i class="fa fa-times"></i>', Url::toRoute(['delete', 'Cid' => $model->contact_id, 'id' => $model->user_id, 'sguid' => $space->guid]), ['class' => 'btn btn-danger btn-xs tt']);
                        }
                    ],
                ],
            ],
        ]);
        ?>

        <?php
        /*
          $this->widget('zii.widgets.grid.CGridView', array(
          'id' => 'user-grid',
          'dataProvider' => $model->resetScope()->search(),
          'filter' => $model,
          'itemsCssClass' => 'table table-hover',
          // 'loadingCssClass' => 'loader',
          'columns' => array(
          array(
          'value' => 'CHtml::image($data->profileImage->getUrl())',
          'type' => 'raw',
          'htmlOptions' => array('width' => '30px'),
          ),
          array(
          'name' => 'username',
          'header' => Yii::t('AdminModule.views_user_index', 'Username'),
          'filter' => CHtml::activeTextField($model, 'username', array('placeholder' => Yii::t('AdminModule.views_user_index', 'Search for username'))),
          ),
          array(
          'name' => 'email',
          'header' => Yii::t('AdminModule.views_user_index', 'Email'),
          'filter' => CHtml::activeTextField($model, 'email', array('placeholder' => Yii::t('AdminModule.views_user_index', 'Search for email'))),
          ),
          array(
          'name' => 'super_admin',
          'header' => Yii::t('AdminModule.views_user_index', 'Admin'),
          'filter' => array("" => Yii::t('AdminModule.views_user_index', 'All'), 0 => Yii::t('AdminModule.views_user_index', 'No'), 1 => Yii::t('AdminModule.views_user_index', 'Yes')),
          ),
          array(
          'class' => 'CButtonColumn',
          'template' => '{view}{update}{deleteOwn}',
          'viewButtonUrl' => 'Yii::app()->createUrl("//user/profile", array("uguid"=>$data->guid));',
          'updateButtonUrl' => 'Yii::app()->createUrl("//admin/user/edit", array("id"=>$data->id));',
          'htmlOptions' => array('width' => '90px'),
          'buttons' => array
          (
          'view' => array
          (
          'label' => '<i class="fa fa-eye"></i>',
          'imageUrl' => false,
          'options' => array(
          'style' => 'margin-right: 3px',
          'class' => 'btn btn-primary btn-xs tt',
          'data-toggle' => 'tooltip',
          'data-placement' => 'top',
          'title' => '',
          'data-original-title' => Yii::t('AdminModule.views_user_index', 'View user profile'),
          ),
          ),
          'update' => array
          (
          'label' => '<i class="fa fa-pencil"></i>',
          'imageUrl' => false,
          'options' => array(
          'style' => 'margin-right: 3px',
          'class' => 'btn btn-primary btn-xs tt',
          'data-toggle' => 'tooltip',
          'data-placement' => 'top',
          'title' => '',
          'data-original-title' => Yii::t('AdminModule.views_user_index', 'Edit user account'),
          ),
          ),
          'deleteOwn' => array
          (
          'label' => '<i class="fa fa-times"></i>',
          'visible' => '$data->id != Yii::app()->user->id', //cannot delete yourself
          'imageUrl' => false,
          'url' => 'Yii::app()->createUrl("//admin/user/delete", array("id"=>$data->id));',
          'deleteConfirmation' => false,
          'options' => array(
          'class' => 'btn btn-danger btn-xs tt',
          'data-toggle' => 'tooltip',
          'data-placement' => 'top',
          'title' => '',
          'data-original-title' => Yii::t('AdminModule.views_user_index', 'Delete user account'),
          ),
          ),
          ),
          ),
          ),
          'pager' => array(
          'class' => 'CLinkPager',
          'maxButtonCount' => 5,
          'nextPageLabel' => '<i class="fa fa-step-forward"></i>',
          'prevPageLabel' => '<i class="fa fa-step-backward"></i>',
          'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
          'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
          'header' => '',
          'htmlOptions' => array('class' => 'pagination'),
          ),
          'pagerCssClass' => 'pagination-container',
          ));
         *
         */
        ?>

    </div>
</div>