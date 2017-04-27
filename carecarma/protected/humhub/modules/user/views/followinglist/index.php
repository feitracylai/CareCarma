<?php

use yii\helpers\Html;
use humhub\modules\user\models\User;

$user = Yii::$app->user->getIdentity();

$followers = $user->getFollowingObjects(User::find());
?>

<div class="panel panel-default members" id="profile-follower-panel">

    <div class="panel-heading"><?php echo Yii::t('UserModule.widgets_views_userFollower', '<strong>Following</strong>'); ?></div>
    <div class="panel-body">
        <?php foreach ($followers as $follower) : ?>
            <a href="<?php echo $follower->getUrl(); ?>">
                <img src="<?php echo $follower->getProfileImage()->getUrl(); ?>" class="img-rounded tt img_margin"
                     style="width: 48px; height: 48px;" data-toggle="tooltip" data-placement="top" >
                <?php echo Html::encode($follower->displayName); ?>
                <hr>
            </a>

        <?php endforeach; ?>


    </div>
</div>