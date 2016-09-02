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
<div class="panel panel-default ">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver', '<strong>Current</strong> Care Receiver'); ?>
    </div>
    <div class="panel-body">
        <p>
            <?php
            if ($dataProvider->totalCount == 0){
                echo ('There is no Care Receiver in your space.');
            }else {
                echo Yii::t('SpaceModule.views_admin_receiver', 'In this overview you can find every registered care receiver in this circle. ');
            }
            ?>

        </p>
        <div class="table-responsive">
            <?php
            $groups = $space->getUserGroups();
//            unset($groups[Space::USERGROUP_OWNER]);
//            unset($groups[Space::USERGROUP_GUEST]);
//            unset($groups[Space::USERGROUP_USER]);

            if ($dataProvider->totalCount == 0){
                echo ('If you want to create account of a care receiver, please "Add Account".');
            }else {
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                    'summary' => false,
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
                                'view' => function($url,$model) use ($space) {
                                    return Html::a('<i class="fa fa-eye"></i>', Url::toRoute(['report', 'sguid' => $space->guid, 'rguid' => $model->user->guid

                                    ]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'View his/her health report']);
                                },
                                'delete' => function ($url,$model) use ($space) {
                                    if ($space->isAdmin()){
                                        return Html::a('<i class="fa fa-times"></i>', Url::toRoute(['delete', 'sguid' => $space->guid, 'rguid' => $model->user->guid

                                        ]), ['class' => 'btn btn-danger btn-xs tt', 'title' => 'Delete account or Move to regular member']);
                                    }
                                    else
                                        return;
                                },
                                'update' => function($url,$model) use ($space){
                                    if ($space->isAdmin()){
                                        return Html::a('<i class="fa fa-pencil"></i>', Url::toRoute(['edit', 'sguid' => $space->guid, 'rguid' => $model->user->guid
                                        ]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'Edit his/her account']);
                                    }
                                    else
                                        return;


                                },
                            ],
                        ],
                    ],
                ]);
            }

            ?>
        </div>
    </div>
</div>
