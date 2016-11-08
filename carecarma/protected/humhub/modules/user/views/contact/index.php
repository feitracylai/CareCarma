<?php

use humhub\compat\CActiveForm;
use humhub\compat\CHtml;
use humhub\modules\user\models\Contact;
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\GridView;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_index', '<strong>Your</strong> people'); ?>
    </div>
    <div class="panel-body">
<!--        --><?//= \humhub\modules\user\widgets\ContactMenu::widget(); ?>
<!--        <p />-->
        <p>
            <?php echo Yii::t('UserModule.views_contact_index', 'In this overview you can find all of your people and their information.'); ?>
        </p>

        <?php


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


                [
                    'label' => Yii::t('UserModule.views_contact_index', 'Circles'),
                    'class' => 'humhub\libs\DropDownGridColumn',
                    'attribute' => 'contact_id',
                    'submitAttributes' => ['contact_id'],
                    'dropDownFunction' => true,
                    'dropDownOptions' =>  function($model) use ($spaces){
                        $selectionName = array();
                        foreach ($spaces as $space){
                            if ($space->isMember($model->contact_user_id)){
                                $selectionName[] = $space->name;
                            }
                        }

                        return $selectionName;
                    },


                ],
//                [
//                    'label' => Yii::t('UserModule.views_contact_index', 'Circles'),
//                    'class' => 'humhub\modules\user\libs\CircleInviteDropDownColumn',
//                    'submitAttributes' => ['contact_user_id'],
//                    'selection' => function ($model) use ($spaces){
//                        $selectionName = array();
//                        foreach ($spaces as $space){
//                            if ($space->isMember($model->contact_user_id)){
//                                $selectionName[] = $space->name;
//                            }
//                        }
//                        return $selectionName;
//                    },
//                    'dropDownOptions' =>  $spaceName,
//
//
//                ],

                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['style' => 'width:80px; min-width:80px;'],
                    'buttons' => [
                        'view' => function($url, $model) use ($user) {
                            return Html::a('<i class="fa fa-plus"></i>',  $user->createUrl('circle-invite', ['cuid' => $model->contact_user_id]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'Invite in Circles', 'data-target' => '#globalModal']);

//                            return Html::a('<i class="fa fa-eye"></i>', $user->createUrl('view', ['id' => $model->contact_id]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'View']);
                        },
                        'update' => function($url, $model) use ($user) {
                            return Html::a('<i class="fa fa-edit"></i>', $user->createUrl('edit', ['id' => $model->contact_id]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'View']);

//                            return;
                        },
                        'delete' => function($url, $model) use ($user) {
                            return Html::a('<i class="fa fa-times"></i>',  $user->createUrl('delete', ['id' => $model->contact_id]), ['class' => 'btn btn-danger btn-xs tt', 'title' => 'Delete']);
                        },

                    ],
                ],
            ],
        ]);
        ?>


    </div>
</div>