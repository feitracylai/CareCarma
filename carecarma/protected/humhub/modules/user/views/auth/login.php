<?php

use \yii\helpers\Url;
use yii\widgets\ActiveForm;
use \humhub\compat\CHtml;

$this->pageTitle = Yii::t('UserModule.views_auth_login', 'Login');
?>

<!-- ******HEADER****** -->
<header class="header">
    <div id="login-logo-container" class="container ">
        <h1 class="logo">
            <a href="../index.html"><span class="logo-icon"></span><span class="text">CareCarma</span></a>
        </h1><!--//logo-->

    </div><!--//container-->
</header><!--//header-->

<div class="container" style="text-align: center;">



    <div class="panel panel-default animated bounceIn" id="login-form"
         style="max-width: 300px; margin: 0 auto 20px; text-align: left;">

        <div class="panel-heading" style="text-align: center"><?php echo Yii::t('UserModule.views_auth_login', '<strong>Log in</strong> to CareCarma'); ?></div>

        <div class="panel-body">

            <?php $form = ActiveForm::begin(['id' => 'account-login-form']); ?>
            <?php echo $form->field($model, 'username')->textInput(['id' => 'login_username', 'placeholder' => $model->getAttributeLabel('username')])->label(false); ?>
            <?php echo $form->field($model, 'password')->passwordInput(['id' => 'login_password', 'placeholder' => $model->getAttributeLabel('password')])->label(false); ?>
            <div class="login-parallel">
                <small>
                    <?php echo $form->field($model, 'rememberMe')->checkbox(); ?>
                    <a href="<?php echo Url::toRoute('/user/auth/recover-password'); ?>"><?php echo Yii::t('UserModule.views_auth_login', 'Forgot your password?') ?></a>
                </small>
            </div>

            <div class="row" style="margin-top: 10px">
                <div class="col-md-12 login-button">
                    <?php echo CHtml::submitButton(Yii::t('UserModule.views_auth_login', 'Sign in'), array('id' => 'login-button', 'class' => 'btn btn-large btn-primary')); ?>
                </div>
            </div>

            <hr>
            <div class="row signup">
                <div class="col-md-8 text-left">
                    <small>
                        <?php echo Yii::t('UserModule.views_auth_login', 'Do not have an account?'); ?>
                        <a href="<?php echo Url::toRoute('/user/auth/signup'); ?>"><?php echo Yii::t('UserModule.views_auth_login', 'Sign up') ?></a>
                    </small>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>


    <?= humhub\widgets\LanguageChooser::widget(); ?>
</div>





<script type="text/javascript">
    $(function () {
        // set cursor to login field
        $('#login_username').focus();
    })

    // Shake panel after wrong validation
<?php if ($model->hasErrors()) { ?>
        $('#login-form').removeClass('bounceIn');
        $('#login-form').addClass('shake');
        $('#register-form').removeClass('bounceInLeft');
        $('#app-title').removeClass('fadeIn');
<?php } ?>

    // Shake panel after wrong validation
<?php if ($registerModel->hasErrors()) { ?>
        $('#register-form').removeClass('bounceInLeft');
        $('#register-form').addClass('shake');
        $('#login-form').removeClass('bounceIn');
        $('#app-title').removeClass('fadeIn');
<?php } ?>

</script>


