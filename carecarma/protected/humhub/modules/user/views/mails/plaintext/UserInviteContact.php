

<?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteSpace', 'You got an invite')); ?>
<?php echo Yii::t('UserModule.views_mails_UserInviteContact', "{username} invited you for network contacts in {name}.", ['username' => $originator->displayName, 'name' => Yii::$app->name]); ?>


<?php echo Yii::t('UserModule.views_mails_UserInviteContact', '<br>{name} a social network to help you stay with your families.Register now to join.', ['name' => Yii::$app->name]); ?>

<?php echo strip_tags(Yii::t('UserModule.views_mails_UserInviteContact', 'Sign up')); ?>: <?php echo urldecode(\yii\helpers\Url::toRoute(["/user/auth/create-account", 'token' => $token], true)); ?>