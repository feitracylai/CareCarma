<?php
$this->pageTitle = Yii::t('UserModule.views_auth_register_success', 'Registration successful');
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
        <div class="panel panel-default" style="max-width: 300px; margin: 0 auto 20px; text-align: left;">
            <div class="panel-heading"><?php echo Yii::t('UserModule.views_auth_register_success', '<strong>Registration</strong> successful!'); ?></div>
            <div class="panel-body">
                <p><?php echo Yii::t('UserModule.views_auth_register_success', 'Please check your email and follow the instructions!'); ?></p>
                <br/>
                <a href="../index.html" class="btn btn-primary"><?php echo Yii::t('UserModule.views_auth_register_success', 'back to home') ?></a>
            </div>
        </div>
    </div>
</div>



