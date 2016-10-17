<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/6/2016
 * Time: 2:26 PM
 */

use yii\bootstrap\ActiveForm;
use humhub\models\Setting;
?>
<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t('UserModule.views_contact_invite', '<strong>Invite</strong> contacts'); ?></h4>
        </div>
        <div class="modal-body">

            <br/>

            <?php if (Setting::Get('internalUsersCanInvite', 'authentication_internal')) : ?>
                <div class="text-center">
                    <ul id="tabs" class="nav nav-tabs tabs-center" data-tabs="tabs">

                        <li class="active tab-external"><a href="#external"
                                                    data-toggle="tab"><?php echo Yii::t('UserModule.views_contact_invite', 'Invite by email'); ?></a>
                        </li>
                    </ul>
                </div>
                <br/>
            <?php endif; ?>

            <div class="tab-content">

                <?php if (Setting::Get('internalUsersCanInvite', 'authentication_internal')) : ?>
                    <div class="tab-pane active" id="external">
                        <?php echo Yii::t('SpaceModule.views_space_invite', 'You can invite external users, which are not registered now. Just add their e-mail addresses separated by comma.'); ?>
                        <br/><br/>
                        <div class="form-group">
                            <?php echo $form->field($model, 'emails')->textArea(['rows' => '3', 'placeholder' => Yii::t('SpaceModule.views_space_invite', 'Email addresses'), 'id' => 'email_invite'])->label(false); ?>
                        </div>
                    </div>

                <?php endif; ?>
            </div>


        </div>
        <div class="modal-footer">

            <?php
            echo \humhub\widgets\AjaxButton::widget([
                'label' => Yii::t('SpaceModule.views_space_invite', 'Send'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    'success' => new yii\web\JsExpression('function(html){ $("#globalModal").html(html); }'),
                    'url' => \yii\helpers\Url::to(['/user/contact/invite']),
                ],
                'htmlOptions' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
            ?>
            <button type="button" class="btn btn-primary"
                    data-dismiss="modal"><?php echo Yii::t('SpaceModule.views_space_invite', 'Close'); ?></button>

            <?php echo \humhub\widgets\LoaderWidget::widget(['id' => 'invite-loader', 'cssClass' => 'loader-modal hidden']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>


<script type="text/javascript">

    // Shake modal after wrong validation
    <?php if ($model->hasErrors()) : ?>
    $('.modal-dialog').removeClass('fadeIn');
    $('.modal-dialog').addClass('shake');

    // check if there is an error at the second tab
    <?php if ($model->hasErrors('inviteExternal')) : ?>
    // show tab external tab
    $('#tabs a:last').tab('show');
    <?php endif; ?>

    <?php endif; ?>

    $('.tab-import a').on('shown.bs.tab', function (e) {
        $('#invite_tag_input_field').focus();
    })

    $('.tab-external a').on('shown.bs.tab', function (e) {
        $('#email_invite').focus();
    })


</script>