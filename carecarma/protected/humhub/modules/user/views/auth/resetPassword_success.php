<?php

use yii\helpers\Url;

$this->pageTitle = Yii::t('UserModule.views_auth_resetPassword', 'Password reset');
?>
<!-- ******HEADER****** -->
<header class="header">
    <div id="login-logo-container" class="container">
        <h1 class="logo">
            <a href="../index.html"><span class="logo-icon"></span><span class="text">CareCarma</span></a>
        </h1><!--//logo-->

    </div><!--//container-->
</header><!--//header-->
<div class="container" style="text-align: center;">

    <div class="row">
        <div class="panel panel-default animated fadeIn" style="max-width: 300px; margin: 0 auto 20px; text-align: left;">
            <div class="panel-heading"><?php echo Yii::t('UserModule.views_auth_resetPassword_success', '<strong>Password</strong> changed!'); ?></div>
            <div class="panel-body">
                <p><?php echo Yii::t('UserModule.views_auth_resetPassword_success', "Your password has been successfully changed!"); ?></p><br/>
                <a href="<?php echo Url::home() ?>" class="btn btn-primary"><?php echo Yii::t('UserModule.views_auth_resetPassword_success', 'Login') ?></a>
            </div>
        </div>
    </div>
</div>