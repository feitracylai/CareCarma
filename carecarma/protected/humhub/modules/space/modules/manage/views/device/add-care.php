<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/23/2016
 * Time: 10:44 AM
 */
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\space\models\Space;
use humhub\modules\space\modules\manage\widgets\DeviceMenu;
use humhub\modules\space\modules\manage\widgets\AddCareMenu;
use humhub\widgets\GridView;
?>

<?= DeviceMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver_add', '<strong>Add</strong> Care Receiver'); ?>

    </div>
    <div class="panel-body">
        <?= AddCareMenu::widget(['space' => $space]); ?>
        <p/>
        <p>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_add', 'If you add someone to be a "Care Receiver", his/her account can be managed by the administers in this circle.'); ?><br>
            <?php echo Yii::t('SpaceModule.views_admin_receiver_add', 'One account just can be set as a Care Receiver in <u>only one</u> circles.'); ?>
        </p>
        <hr/>
        <ul class="media-list">
            <!-- BEGIN: Results -->
            <?php foreach ($users as $user) : ?>
                            <?php $userMembership = \humhub\modules\space\models\Membership::findOne(['space_id' => $space->id, 'user_id' => $user->id]); ?>

                <li>
                    <div class="media" id="media-<?php echo $user->guid; ?>">


                        <div class="pull-right" >
                            <?php if ($userMembership->add_care == '0'): ?>
                                <div style="padding: 10px 20px 0">
                                    <?php echo Yii::t('SpaceModule.views_admin_receiver_add', 'Waiting for accept... &nbsp&nbsp&nbsp&nbsp&nbsp'); ?>
<!--                                    Waiting for accept...-->
                                    <?php echo Html::a('<i class="fa fa-times"></i> ', $space->createUrl('device/add-care-cancel', ['linkId' => $user->id]), array('class' => 'btn btn-danger btn-xs tt', 'data-method' => 'POST', 'data-original-title' => 'cancel', 'data-confirm' => 'Are you sure? ')); ?>
                                </div>
                            <?php else: ?>
                            <?php echo Html::a('<i class="fa fa-plus"></i> '.Yii::t('UserModule.views_contact_connect', 'Care'), $space->createUrl('device/care-remind',['linkId' => $user->id]), array('class' => 'btn btn-primary','data-target' => '#globalModal' )); ?>
<!--                                --><?php //echo Html::a('<i class="fa fa-plus"></i> '.Yii::t('UserModule.views_contact_connect', 'Care'), $space->createUrl('device/add-care', ['doit' => 2, 'linkId' => $user->id]), array('class' => 'btn btn-primary', 'data-method' => 'POST')); ?>
                            <?php endif; ?>
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


    </div>
</div>
