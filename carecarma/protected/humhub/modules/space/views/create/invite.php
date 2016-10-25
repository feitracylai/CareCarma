<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use humhub\models\Setting;
use yii\helpers\Html;
?>

<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t('SpaceModule.views_space_invite', '<strong>Invite</strong> members'); ?></h4>
        </div>
        <div class="modal-body">

            <?php if (count($users) == 0){ ?>

                <p><?php echo Yii::t('SpaceModule.views_space_invite', 'No users in your people! Or they already in this circle.'); ?></p>
            <?php }else{ ?>
        </div>

    <hr>
        <ul class="media-list">
            <!-- BEGIN: Results -->
            <?php foreach ($users as $user) : ?>
                <li>
                    <div class="media" id="media-<?php echo $user->guid; ?>">
                        <?php $membership = \humhub\modules\space\models\Membership::findOne(['space_id' => $space_id, 'user_id' => $user->id, 'status' => \humhub\modules\space\models\Membership::STATUS_INVITED]) ?>
                        <div class="pull-right" >
                            <?php if($membership == null){
                                echo Html::a('<i class="fa fa-plus"></i> '.Yii::t('SpaceModule.views_space_invite', 'Invite'),
                                    $space->createUrl('/space/membership/invite',
                                        ['doit' => 2,
                                        'user_id' => $user->id,
                                        'spaceId' => $space_id]),
                                    array('class' => 'btn btn-primary  pull-right space-invite','data-target' => '#globalModal'));
                            } else { ?>
                                <a class="btn btn-default pull-right" disabled><i class="fa fa-plus"></i> Request Sent</a>
                            <?php
//                            echo Html::a(Yii::t('SpaceModule.views_space_invite', 'Cancel Request'),'',array('class' => 'btn btn-danger  pull-right'));

                            }?>
                        </div>


                        <a href="#" class="pull-left contact"">
                        <img class="media-object img-rounded"
                             src="<?php echo $user->getProfileImage()->getUrl(); ?>" width="50"
                             height="50" alt="50x50" data-src="holder.js/50x50"
                             style="width: 50px; height: 50px;">
                        </a>

                        <div class="media-body">
                            <h4 class="media-heading">
                                <?php echo Html::encode($user->displayName); ?>

                            </h4>
                        </div>


                    </div>



                </li>

            <?php endforeach; ?>
            <!-- END: Results -->
        </ul>
    <?php } ?>
        <div class="modal-footer">
            <hr>
            <br>


            <button type="button" class="btn btn-primary"
                    data-dismiss="modal"><?php echo Yii::t('SpaceModule.views_space_invite', 'Done'); ?></button>

            <?php echo \humhub\widgets\LoaderWidget::widget(['id' => 'invite-loader', 'cssClass' => 'loader-modal hidden']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>


<script>

    $('.space-invite').on('click', function(e){
        location.reload();

    });)


</script>