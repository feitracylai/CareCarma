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
                id="myModalLabel"><?php echo Yii::t('SpaceModule.views_add_remind', '<strong>Remind</strong>'); ?></h4>
        </div>

        <div class="modal-body">
            <br/>

            <div class="tab-content">
                <div class="tab-pane active" id="internal" style="font-size: 15px; text-align: center">


                    <?php if ($status == 'care'): ?>
                        <?php echo Yii::t('SpaceModule.views_add_remind', 'Sorry, as an Care Receiver of other circle he/she is not able to be cared in this circle. Please check.'); ?>
                        <!--                        --><?php //elseif ($status == 'owner'): ?>
                        <!--                            --><?php //echo Yii::t('SpaceModule.views_care_remind', 'Sorry, as an owner of other circle he/she are not able to be cared in this circle. Please assign another owner or delete them.'); ?>
                    <?php else: ?>
                        <?php echo Yii::t('SpaceModule.views_add_remind', 'He/She is an <u>administrator</u> in this circles. If he/she was a Care Receiver, he/she should not have the permission to manage this circle. '); ?><br/><br/>
                        <?php echo Yii::t('SpaceModule.views_add_remind', '<strong>Are you sure?</strong>'); ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="modal-footer">
            <?php if ($status == 'admin'): ?>
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('SpaceModule.views_space_statusInvite', 'Later'); ?></button>
                <?php echo Html::a(Yii::t('SpaceModule.views_add_remind', 'Sure !'), $space->createUrl('device/add-care', ['doit' => 2, 'linkId' => $userId]), array('class' => 'btn btn-info', 'data-method' => 'POST')); ?>

            <?php else: ?>
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('SpaceModule.views_add_remind', 'Ok'); ?></button>
            <?php endif; ?>

        </div>
    </div>
</div>

<script type="text/javascript">

    // Replace the standard checkbox and radio buttons
    $('.modal-dialog').find(':checkbox, :radio').flatelements();

    // show Tooltips on elements inside the views, which have the class 'tt'
    $('.tt').tooltip({html: false});

</script>
