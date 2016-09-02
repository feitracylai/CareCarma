<?php

use yii\helpers\Html;
?>

<div class="panel panel-default members" id="space-members-panel">

    <div class="panel-heading"><?php echo Yii::t('SpaceModule.widgets_views_spaceMembers', '<strong>Circle Members</strong>'); ?></div>
    <div class="panel-body">
        <?php foreach ($members as $membership) : ?>
            <?php $user = $membership->user; ?>
            <?php if($membership->status === \humhub\modules\space\models\Membership::STATUS_MEMBER) : ?>
                <a href="<?php echo $user->getUrl(); ?>">
                    <img src="<?php echo $user->getProfileImage()->getUrl(); ?>" class="img-rounded tt img_margin"
                         style="width: 48px; height: 48px;" data-toggle="tooltip" data-placement="top" >
                    <?php echo Html::encode($user->displayName); ?>
                    <hr>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>


    </div>
</div>