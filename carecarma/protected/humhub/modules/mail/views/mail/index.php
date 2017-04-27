<?php

use yii\helpers\Html;

if ($messageId != "") {
    $this->registerJs('loadMessage(' . Html::encode($messageId) . ');');
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 layout-below-top-second">

            <div class="panel panel-default">
<!--                <div class="panel-heading">-->
<!--                    --><?php //echo Yii::t('MailModule.views_mail_index', 'Conversations') ?>
<!--                    --><?php //echo Html::a(Yii::t('MailModule.views_mail_index', 'New message'), ['/mail/mail/create'], array('class' => 'btn btn-info pull-right', 'data-target' => '#globalModal')); ?>
<!--                </div>-->

                <div class="panel-heading collapse-toggle hidden-md hidden-lg" type="button" data-toggle="collapse" data-parent="custom-collapse" data-target="#side_menu_0"  style="cursor: pointer;" >
                    <?php echo Yii::t('MailModule.views_mail_index', 'Conversations') ?>
                    <?php echo Html::a(Yii::t('MailModule.views_mail_index', 'New message'), ['/mail/mail/create'], array('class' => 'btn btn-info pull-right', 'data-target' => '#globalModal')); ?>
                    <i class="fa fa-angle-down"></i>
                </div>
                <div class="panel-heading hidden-xs hidden-sm">
                    <?php echo Yii::t('MailModule.views_mail_index', 'Conversations') ?>
                    <?php echo Html::a(Yii::t('MailModule.views_mail_index', 'New message'), ['/mail/mail/create'], array('class' => 'btn btn-info pull-right', 'data-target' => '#globalModal')); ?>
                </div>

                <hr>
                <div class="list-group pos-absolute collapse " id="side_menu_0">
                    <ul id="inbox" class="media-list" style="background-color: white">
                        <?php if (count($userMessages) != 0) : ?>
                            <?php foreach ($userMessages as $userMessage) : ?>
                                <?php echo $this->render('_messagePreview', array('userMessage' => $userMessage)); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="placeholder"><?php echo Yii::t('MailModule.views_mail_index', 'There are no messages yet.'); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>


            <div class="pagination-container">
                <?= \humhub\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
            </div>
        </div>
        <div class="col-md-8 messages">
            <div id="mail_message_details">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>
