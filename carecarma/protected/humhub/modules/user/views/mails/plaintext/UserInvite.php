

<?php echo Yii::t('UserModule.mail', '{username} invited you to {name}.', ['username' => $originator->displayName, 'name' => Yii::$app->name]); ?>


<?php echo Yii::t('UserModule.mail', 'Click here to create an account:'); ?>

<?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteSelf', 'Sign up')); ?>: <?php echo urldecode(\yii\helpers\Url::toRoute(["/user/auth/create-account", 'token' => $token], true)); ?>
