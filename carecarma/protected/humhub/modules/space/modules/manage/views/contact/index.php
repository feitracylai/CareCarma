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
    <div class="panel-heading"><?php echo Yii::t('SpaceModule.views_admin_receiver_contact', '<strong>Contacts</strong>'); ?></div>

    <div class="panel-body">
        <!---Care Receiver can not add contacts->
<!--        <?//=ContactMenu::widget(['space' => $space]); ?>-->
<!--        <br/>-->
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_contact', "In this overview you can find and manage
              {first} {last}'s contacts.", array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
        </p>

        <?php
        $relationship = Yii::$app->params['availableRelationship'];

        echo GridView::widget([
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'summary' => false,
            'columns' => [
                [
                    'label' => Yii::t('UserModule.views_contact_index', 'Name'),
                    'attribute' => 'contact_first',
                    'format' => 'raw',
                    'value' =>
                        function ($data) {
                            return Yii::t('UserModule.views_contact_index', '{firstname} {lastname}', ['{firstname}' => $data->contact_first, '{lastname}' => $data->contact_last]);
                        },

                ],
                'nickname',
                [
                    'label' => Yii::t('UserModule.views_contact_index', 'Relationship to {first} {last}', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)),
                    'class' => 'humhub\libs\DropDownGridColumn',
                    'attribute' => 'relation',
                    'submitAttributes' => ['contact_id'],
                    'dropDownOptions' =>  $relationship,
                    'value' =>
                        function ($data) use ($relationship) {

                            return $relationship[$data->relation];
                        },


                ],
                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['style' => 'width:80px; min-width:80px;'],
                    'buttons' => [
                        'view' => function($url, $model) use ($space, $user) {
//                            return Html::a('<i class="fa fa-eye"></i>', Url::toRoute(['view', 'Cid' => $model->contact_id, 'id' => $model->user_id, 'sguid' => $space->guid]), ['class' => 'btn btn-primary btn-xs tt']);
//                            Yii::getLogger()->log($user->id, \yii\log\Logger::LEVEL_INFO, 'MyLog');
                            return Html::a('<i class="fa fa-eye"></i>', $space->createUrl('edit', ['Cid' => $model->contact_id, 'rguid' => $user->guid]), ['class' => 'btn btn-primary btn-xs tt']);

                        },
                        'update' => function($url, $model) use ($space, $user) {
//                            return Html::a('<i class="fa fa-pencil"></i>', $space->createUrl('edit', ['Cid' => $model->contact_id, 'rguid' => $user->guid]), ['class' => 'btn btn-primary btn-xs tt']);
                            return;
                        },
                        'delete' => function($url, $model) use ($space, $user) {
                            if ($space->isMember($model->contact_user_id)){
                                return;
                            }

                            return Html::a('<i class="fa fa-times"></i>', $space->createUrl('delete', ['Cid' => $model->contact_id, 'rguid' => $user->guid]), ['class' => 'btn btn-danger btn-xs tt']);
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