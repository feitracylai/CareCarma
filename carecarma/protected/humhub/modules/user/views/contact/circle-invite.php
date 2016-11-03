<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/31/2016
 * Time: 5:24 PM
 */

use yii\helpers\Html;

?>


<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t('UserModule.views_contact_circleInvite', '<strong>Invite</strong> in circles'); ?></h4>
        </div>

        <div class="modal-body">


            <?php if (count($spacesInvite) == 0):  ?>
            <p><?php echo Yii::t('UserModule.views_contact_circleInvite', 'This PEOPLE already in all of your circles.'); ?></p>
            <?php else: ?>

        </div>

        <hr>
        <ul class="media-list">
            <?php foreach ($spacesInvite as $space) : ?>
                <li>
                    <div class="media" id="media-<?php echo $space->guid; ?>">
                        <?php $membership = \humhub\modules\space\models\Membership::findOne(['space_id' => $space->id, 'user_id' => $cuid, 'status' => \humhub\modules\space\models\Membership::STATUS_INVITED]) ?>
                        <div class="pull-right" >
                            <?php if($membership == null){
                                echo Html::a('<i class="fa fa-plus"></i> '.Yii::t('UserModule.views_contact_circleInvite', 'Invite'), $user->createUrl('/user/contact/circle-invite', ['doit' => 2, 'space_id' => $space->id, 'cuid' => $cuid]), array('class' => 'btn btn-primary  pull-right space-invite','data-target' => '#globalModal'));
                            } else { ?>
                                <a class="btn btn-default " disabled><i class="fa fa-plus"></i> Invite Sent</a>
                                <?php echo Html::a(Yii::t('UserModule.views_contact_circleInvite', 'Cancel Invite'),  $user->createUrl('/user/contact/circle-invite', ['doit' => 3, 'space_id' => $space->id, 'cuid' => $cuid]), array('class' => 'btn btn-danger pull-right', 'data-target' => '#globalModal'));
                            } ?>
                        </div>

                        <a href="#" class="pull-left contact"">
                        <?php echo \humhub\modules\space\widgets\Image::widget([
                            'space' => $space,
                            'width' => 40,
                            'htmlOptions' => [
                                'class' => 'media-object img-rounded',
                            ],
                            'link' => 'true',
                            'linkOptions' => [
                                'class' => 'pull-left',
                            ],
                        ]); ?>
                        </a>

                        <div class="media-body">
                            <h4 class="media-heading">
                                <?php echo Html::encode($space->name); ?>

                            </h4>
                        </div>


                    </div>
                </li>
            <?php endforeach; ?>
            <!-- END: Results -->
        </ul>

        <?php endif; ?>

        <div class="modal-footer">

            <button type="button" class="btn btn-primary"
                    data-dismiss="modal"><?php echo Yii::t('UserModule.views_contact_circleInvite', 'Close'); ?></button>

            <?php echo \humhub\widgets\LoaderWidget::widget(['id' => 'invite-loader', 'cssClass' => 'loader-modal hidden']); ?>
        </div>
    </div>

</div>
