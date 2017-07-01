<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\models\Setting;
?>
<?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteSpace', 'You got a <strong>circle</strong> invite')); ?>


<?php echo Html::encode($originator->displayName); ?> <?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteSpace', 'invited you to the circle:')); ?> <?php echo Html::encode($space->name); ?> in <?php echo Html::encode(Yii::$app->name); ?>

<?php echo strip_tags(str_replace(["\n","<br>"], [" ","\n"], Yii::t('UserModule.views_mails_UserInviteSpace', '<br>A social network to increase your caring and communication in your circle.Register now
to join.'))); ?>


<?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteSpace', 'Sign up now')); ?>: <?php echo urldecode(Url::to(['/user/auth/create-account', 'token' => $token], true)); ?>
