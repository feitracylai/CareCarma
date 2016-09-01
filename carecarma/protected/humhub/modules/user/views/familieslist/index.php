<?php

use yii\helpers\Html;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;

$user = Yii::$app->user->getIdentity();

$showSpaces = 30;
$spaces = array();
$i = 0;

foreach (Membership::GetUserSpaces($user->id) as $space) {
    if ($space->visibility == Space::VISIBILITY_NONE)
        continue;
    if ($space->status != Space::STATUS_ENABLED)
        continue;
    $i++;

    if ($i > $showSpaces)
        break;

    $spaces[] = $space;
}

?>

<div class="panel panel-default members" id="profile-follower-panel">

    <div class="panel-heading"><?php echo Yii::t('UserModule.widgets_views_userFollower', '<strong>Member of Circles</strong>'); ?></div>
    <div class="panel-body">
        <?php foreach ($spaces as $follower) : ?>
            <a href="<?php echo $follower->getUrl(); ?>">
                <img src="<?php echo $follower->getProfileImage()->getUrl(); ?>" class="img-rounded tt img_margin"
                     style="width: 48px; height: 48px;" data-toggle="tooltip" data-placement="top" >
                <?php echo Html::encode($follower->displayName); ?>
                <hr>
            </a>

        <?php endforeach; ?>


    </div>
</div>