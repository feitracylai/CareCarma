<?php

use humhub\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use humhub\modules\space\modules\manage\widgets\DeviceMenu;
?>
<?= DeviceMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver', '<strong>Care</strong> Receiver'); ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            $groups = $space->getUserGroups();
//            unset($groups[Space::USERGROUP_OWNER]);
//            unset($groups[Space::USERGROUP_GUEST]);
//            unset($groups[Space::USERGROUP_USER]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'user.username',
                    'user.email',
                    'user.profile.firstname',
                    'user.profile.lastname',

                    [
                        'attribute' => 'last_visit',
                        'format' => 'raw',
                        'value' =>
                            function ($data) use (&$groups) {
                                if ($data->last_visit == '') {
                                    return Yii::t('SpaceModule.views_admin_receiver', 'never');
                                }

                                return humhub\widgets\TimeAgo::widget(['timestamp' => $data->last_visit]);
                            }
                    ],
                    [
                        'header' => Yii::t('SpaceModule.views_admin_receiver', 'Actions'),
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function () {
                                return;
                            },
                            'delete' => function ($url, $model) use ($space) {
                                if ($space->isSpaceOwner($model->user->id) || Yii::$app->user->id == $model->user->id) {
                                    return;
                                }
//                                return Html::a('<i class="fa fa-times"></i>', Url::toRoute(['delete', 'user_id' => $model->user->id]), ['class' => 'btn btn-danger btn-xs tt']);
                                return Html::a('<i class="fa fa-times"></i>', $space->createUrl('index', ['userGuid' => $model->user->guid]), ['class' => 'btn btn-danger btn-xs tt', 'data-method' => 'POST', 'data-confirm' => 'Are you sure?']);
                            },
                            'update' => function($url, $model) use ($space){
                                return Html::a('<i class="fa fa-pencil"></i>', Url::toRoute(['edit', 'sguid' => $space->guid, 'id' => $model->user->id

                                ]), ['class' => 'btn btn-primary btn-xs tt']);
                            },
                        ],
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
