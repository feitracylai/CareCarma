<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/22/2016
 * Time: 11:01 AM
 */
use yii\helpers\Html;
?>

<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t('SpaceModule.views_care_remind', '<strong>Accept </strong>Remind'); ?></h4>
        </div>

        <div class="modal-body">
            <br/>

                <div class="tab-content">
                    <div class="tab-pane active" id="internal" style="font-size: 15px; text-align: center">

                        <?php echo Yii::t('SpaceModule.views_care_remind', 'If you accepted, the admins in this circle can edit your account information.'); ?>
                        <?php if ($status == 'thisAdmin'): ?>
                            <?php echo Yii::t('SpaceModule.views_care_remind', 'You will not have the permission to manage this circle.'); ?>
                        <?php endif; ?>
                        <?php echo Yii::t('SpaceModule.views_care_remind', '<strong>Are you sure?</strong>'); ?>
                    </div>
                </div>
        </div>

        <div class="modal-footer">
<!--            --><?php //if ($status == 'thisAdmin'): ?>
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('SpaceModule.views_space_statusInvite', 'Later'); ?></button>
                <?php echo Html::a(Yii::t('UserModule.views_contact_connect', 'Sure !'), $space->createUrl('device/care-accepted'), array('class' => 'btn btn-info', 'data-method' => 'POST')); ?>
<!---->
<!--            --><?php //else: ?>
<!--            <button type="button" class="btn btn-primary" data-dismiss="modal">--><?php //echo Yii::t('SpaceModule.views_space_statusInvite', 'Ok'); ?><!--</button>-->
<!--            --><?php //endif; ?>

        </div>
    </div>
</div>

<script type="text/javascript">

    // Replace the standard checkbox and radio buttons
    $('.modal-dialog').find(':checkbox, :radio').flatelements();

    // show Tooltips on elements inside the views, which have the class 'tt'
    $('.tt').tooltip({html: false});

</script>
