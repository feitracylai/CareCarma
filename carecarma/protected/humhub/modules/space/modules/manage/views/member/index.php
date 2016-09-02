<?php

use humhub\widgets\GridView;
use yii\helpers\Html;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use humhub\modules\space\modules\manage\widgets\MemberMenu;
?>
<?= MemberMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_members', '<strong>Manage</strong> members'); ?>
    </div>
    <div class="panel-body">
        <?php echo Yii::t('SpaceModule.views_admin_members', 'If you set someone in "Care Receiver" Group, his/her account can be managed by the administers in this circle. And All of the member in this circle can see the health report of his/her device.'); ?>
        <div class="table-responsive">
            <?php
            $groups = $space->getUserGroups();
            unset($groups[Space::USERGROUP_OWNER]);
            unset($groups[Space::USERGROUP_GUEST]);
            unset($groups[Space::USERGROUP_USER]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => false,
                'columns' => [
                    'user.username',
                    'user.profile.firstname',
                    'user.profile.lastname',
                    [
                        'label' => Yii::t('SpaceModule.views_admin_members', 'Group'),
                        'class' => 'humhub\libs\DropDownGridColumn',
                        'attribute' => 'group_id',
                        'submitAttributes' => ['user_id'],
                        'readonly' => function ($data) use ($space) {
                    if ($space->isSpaceOwner($data->user->id)) {
                        return true;
                    }
                            if ($data->group_id == $space::USERGROUP_MODERATOR){
                                return true;
                            }
                    return false;
                },
                        'filter' => $groups,
                        'dropDownOptions' => $groups,
                        'value' =>
                        function ($data) use (&$groups, $space) {
                    return $groups[$data->group_id];
                }
                    ],
                    [
                        'attribute' => 'last_visit',
                        'format' => 'raw',
                        'value' =>
                        function ($data) use (&$groups) {
                            if ($data->last_visit == '') {
                                return Yii::t('SpaceModule.views_admin_members', 'never');
                            }

                            return humhub\widgets\TimeAgo::widget(['timestamp' => $data->last_visit]);
                        }
                            ],
                            [
                                'header' => Yii::t('SpaceModule.views_admin_members', 'Actions'),
                                'class' => 'yii\grid\ActionColumn',
                                'buttons' => [
                                    'view' => function () {
                                        return;
                                    },
                                    'delete' => function ($url, $model) use ($space) {
                                        if ($space->isSpaceOwner($model->user->id) || Yii::$app->user->id == $model->user->id) {
                                            return;
                                        }
                                        return Html::a(Yii::t('SpaceModule.views_admin_members', 'Remove'), $space->createUrl('reject-applicant', ['userGuid' => $model->user->guid]), ['class' => 'btn btn-danger btn-sm', 'data-method' => 'POST', 'data-confirm' => 'Are you sure?']);
                                    },
                                            'update' => function () {
                                        return;
                                    },
                                        ],
                                    ],
                                ],
                            ]);
                            ?>
        </div>
    </div>
</div>
