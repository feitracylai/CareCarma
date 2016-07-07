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
        <?php echo Yii::t('UserModule.views_contact_index', '<strong>Contact</strong> information'); ?>
    </div>
    <div class="panel-body">
        <?= \humhub\modules\user\widgets\ContactMenu::widget(); ?>
        <p />
        <p>
            <?php echo Yii::t('UserModule.views_contact_index', 'In this overview you can find all of your contacts and manage them.'); ?>
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
                    'label' => Yii::t('UserModule.views_contact_index', 'Relationship'),
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
                        'view' => function($url, $model) use ($user) {
                            return Html::a('<i class="fa fa-eye"></i>', $user->createUrl('view', ['id' => $model->contact_id]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'View']);
                        },
                        'update' => function($url, $model) use ($user) {
                            return Html::a('<i class="fa fa-pencil"></i>',  $user->createUrl('edit', ['id' => $model->contact_id]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'Edit']);
                        },
                        'delete' => function($url, $model) use ($user) {
                            return Html::a('<i class="fa fa-times"></i>',  $user->createUrl('delete', ['id' => $model->contact_id]), ['class' => 'btn btn-danger btn-xs tt', 'title' => 'Delete']);
                        }
                    ],
                ],
            ],
        ]);
        ?>


    </div>
</div>