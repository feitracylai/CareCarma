<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/20/2017
 * Time: 3:08 PM
 */
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
use humhub\widgets\GridView;
use yii\helpers\Html;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('ReminderModule.views_receiver_index', '<strong>Set</strong> Reminders'); ?>
    </div>

    <div class="panel-body">
        <?php if (\humhub\modules\user\models\Device::find()->where(['user_id' => $receiver->id, 'activate' => 1])->count() == 0): ?>

        <p>
            <?php echo Yii::t('ReminderModule.views_receiver_index', '{firstname} {lastname} does not have a CoSMoS device, please help him/her to',
                array('{firstname}' => $receiver->profile->firstname, '{lastname}' => $receiver->profile->lastname)) ?> <?php echo Html::a('<u style="color: #4CACC6">add one.</u>', $space->createUrl('/space/manage/device/device',['rguid' => $receiver->guid]));?>
        </p>

        <?php else: ?>
        <p>
            <?php echo Yii::t('ReminderModule.views_receiver_index', 'Please help {firstname} {lastname} set reminders in his/her CoSMoS device here.',
                array('{firstname}' => $receiver->profile->firstname, '{lastname}' => $receiver->profile->lastname)) ?>
        </p>

                <a href="<?php echo $space->createUrl('add', ['rguid' => $receiver->guid]); ?>" class="btn btn-primary"
                   data-target="#globalModal"><i
                        class="fa fa-plus"></i> <?php echo Yii::t('ReminderModule.views_receiver_index', 'Add Reminder'); ?></a>

        <!----test----->
<!--        <a href="--><?php //echo $space->createUrl('add-test', ['rguid' => $receiver->guid]); ?><!--" class="btn btn-primary"-->
<!--           data-target="#globalModal"><i-->
<!--                class="fa fa-plus"></i> --><?php //echo Yii::t('ReminderModule.views_receiver_index', 'Add Reminder'); ?><!--</a>-->
        <!----test----->

        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'title',


                'description',

                [
                    'label' => Yii::t('ReminderModule.views_receiver_index', 'Updated_by'),
                    'attribute' => 'update_user_id',
                    'format' => 'raw',
                    'value' =>
                        function ($data) {
                            $profile = \humhub\modules\user\models\Profile::findOne(['user_id' => $data->update_user_id]);
                            return Yii::t('ReminderModule.views_receiver_index', '{firstname} {lastname}', ['{firstname}' => $profile->firstname, '{lastname}' => $profile->lastname]);
                        },

                ],

//                'user.profile.firstname',
//                'user.profile.lastname',


                [
                    'header' => Yii::t('ReminderModule.views_receiver_index', 'Actions'),
                    'class' => 'yii\grid\ActionColumn',
//                    'options' => ['style' => 'width:80px; min-width:80px;'],
                    'buttons' => [
                        'view' => function() {
                            return ;
                        },
                        'update' => function($url, $model) use  ($space, $receiver) {

                            return Html::a(Yii::t('ReminderModule.views_receiver_index', 'Edit'), $space->createUrl('edit', ['rguid' => $receiver->guid, 'id' => $model->id]), ['class' => 'btn btn-info btn-sm', 'data-target' => '#globalModal']);
                        },
                        'delete' => function($url, $model) use ($space){
//                            return Html::a(Yii::t('ReminderModule.views_receiver_index', 'Remove'), $space->createUrl('delete', ['id' => $model->id]), ['class' => 'btn btn-danger btn-sm', 'data-target' => '#globalModal']);
//                            Yii::getLogger()->log($url, \yii\log\Logger::LEVEL_INFO, 'MyLog');
                            return humhub\widgets\ModalConfirm::widget(array(
                                'uniqueID' => 'modal_delete_remind_' . $model->id,
                                'linkOutput' => 'a',
                                'cssClass' => 'btn btn-danger btn-sm',
                                'title' => Yii::t('ReminderModule.views_receiver_index', '<strong>Confirm</strong> deleting'),
                                'message' => Yii::t('ReminderModule.views_receiver_index', 'Do you really want to delete this task?'),
                                'buttonTrue' => Yii::t('ReminderModule.views_receiver_index', 'Delete'),
                                'buttonFalse' => Yii::t('ReminderModule.views_receiver_index', 'Cancel'),
                                'linkContent' => 'remove',
                                'linkHref' => $url.'&sguid='.$space->guid,
                                'confirmJS' => "window.location.reload()",
                            ));
                        }
                    ],
                ],
            ],
        ]);
        ?>

        <?php endif; ?>
    </div>
</div>


