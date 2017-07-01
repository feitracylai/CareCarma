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
            <?php echo Yii::t('SpaceModule.views_admin_receiver_contact', "In this overview you can find
              {first} {last}'s all contacts, and manage the relationship of this contact people to {first} {last}. 
              <br>And you can check the primary contact list in {first} {last}'s device(s).", array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
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
//                'nickname',


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
                    'label' => Yii::t('UserModule.views_contact_index', 'CoSMoS Phone App (max: 7)'),
                    'options' => ['style' => 'width:10%'],
                    'headerOptions' => ['style' => 'color: #888'],
                    'class' => 'humhub\libs\CheckGridColumn',
                    'attribute' => 'phone_primary_number',
                    'submitAttributes' => ['contact_id'],
                    'htmlOptions' => [
                        'class' => 'phone-primary'
                    ],
                ],

                [
                    'label' => Yii::t('UserModule.views_contact_index', 'CoSMoS Watch App (max: 6)'),
                    'options' => ['style' => 'width:10%; '],
                    'headerOptions' => ['style' => 'color: #888'],
                    'class' => 'humhub\libs\CheckGridColumn',
                    'attribute' => 'watch_primary_number',
                    'submitAttributes' => ['contact_id'],
                    'htmlOptions' => [
                        'class' => 'watch-primary'
                    ]
                ],

                [
                    'label' => Yii::t('UserModule.views_contact_index', 'CareCarma Watch (max: 5)'),
                    'options' => ['style' => 'width:8%; '],
                    'headerOptions' => ['style' => 'color: #888'],
                    'class' => 'humhub\libs\CheckGridColumn',
                    'attribute' => 'carecarma_watch_number',
                    'submitAttributes' => ['contact_id'],
                    'htmlOptions' => [
                        'class' => 'carecarma-watch'
                    ]
                ],

                [
                    'label' => Yii::t('UserModule.views_contact_index', 'CoSMoS Vue'),
                    'options' => ['style' => 'width:5%; '],
                    'headerOptions' => ['style' => 'color: #888'],
                    'class' => 'humhub\libs\CheckGridColumn',
                    'attribute' => 'glass_primary_number',
                    'submitAttributes' => ['contact_id'],
                    'htmlOptions' => [
                        'class' => 'glass-primary'
                    ]
                ],



                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['style' => 'width:80px; min-width:80px;'],
                    'buttons' => [
                        'view' => function($url, $model) use ($space, $user) {
//                            return Html::a('<i class="fa fa-eye"></i>', Url::toRoute(['view', 'Cid' => $model->contact_id, 'id' => $model->user_id, 'sguid' => $space->guid]), ['class' => 'btn btn-primary btn-xs tt']);
//                            Yii::getLogger()->log($user->id, \yii\log\Logger::LEVEL_INFO, 'MyLog');
//                            return Html::a('<i class="fa fa-eye"></i>', $space->createUrl('edit', ['Cid' => $model->contact_id, 'rguid' => $user->guid]), ['class' => 'btn btn-primary btn-xs tt']);

                        },
                        'update' => function($url, $model) use ($space, $user) {
                            return Html::a('<i class="fa fa-pencil"></i>', $space->createUrl('edit', ['Cid' => $model->contact_id, 'rguid' => $user->guid]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'Edit']);
                            return;
                        },
                        'delete' => function($url, $model) use ($space, $user) {
                            if ($space->isMember($model->contact_user_id)){
                                return;
                            }

                            return Html::a('<i class="fa fa-times"></i>', $space->createUrl('delete', ['Cid' => $model->contact_id, 'rguid' => $user->guid]), ['class' => 'btn btn-danger btn-xs tt', 'title' => 'Delete']);
                        }
                    ],
                ],
            ],
        ]);
        ?>

        <script type="text/javascript">
            limit();

            $('.checkCell').change(function () {
                limit();
            });

            function limit() {
                if ($('.phone-primary:checked').length >= 7){
                    $('.phone-primary:not(:checked)').attr('disabled', true);
                } else {
                    $('.phone-primary:not(:checked)').removeAttr('disabled');
                }

                if ($('.watch-primary:checked').length >= 6){
                    $('.watch-primary:not(:checked)').attr('disabled', true);
                }else {
                    $('.watch-primary:not(:checked)').removeAttr('disabled');
                }

                if ($('.carecarma-watch:checked').length >= 5){
                    $('.carecarma-watch:not(:checked)').attr('disabled', true);
                } else {
                    $('.carecarma-watch:not(:checked)').removeAttr('disabled');
                }
            }

        </script>

    </div>
</div>