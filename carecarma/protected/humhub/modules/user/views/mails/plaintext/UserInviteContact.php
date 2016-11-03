

<?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteSpace', 'You got an invite')); ?>
<?php echo Yii::t('UserModule.views_mails_UserInviteContact', "{username} invited you to join {name}.", ['username' => $originator->displayName, 'name' => Yii::$app->name]); ?>


<?php echo Yii::t('UserModule.views_mails_UserInviteContact', '<br>Check it out!'); ?>

<?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteContact', 'Sign up now')); ?>: <?php echo urldecode(\yii\helpers\Url::toRoute(["/user/auth/create-account", 'token' => $token], true)); ?>