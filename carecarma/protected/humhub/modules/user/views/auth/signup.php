<?php

use \yii\helpers\Url;
use yii\widgets\ActiveForm;
use \humhub\compat\CHtml;

$this->pageTitle = Yii::t('UserModule.views_auth_Signup', 'Signup');
?>

<!-- ******HEADER****** -->
<header class="header">
    <div class="container">
        <h1 class="logo">
            <a href="../index.html"><span class="logo-icon"></span><span class="text">CareCarma</span></a>
        </h1><!--//logo-->

    </div><!--//container-->
</header><!--//header-->


<div class="container" style="text-align: center;">



    <?php if ($canRegister) : ?>
        <div id="register-form"
             class="panel panel-default animated bounceInLeft"
             style="max-width: 300px; margin: 0 auto 20px; text-align: center;">

            <div class="panel-heading"><?php echo Yii::t('UserModule.views_auth_signup', '<strong>Sign up</strong> for CareCarma') ?></div>

            <div class="panel-body">


                <?php $form = ActiveForm::begin(['id' => 'account-register-form']); ?>

                <?php echo $form->field($registerModel, 'email')->textInput(['id' => 'register-email', 'placeholder' => $registerModel->getAttributeLabel('email')])->label(false); ?>
               <div class="register-button">
                   <?php echo CHtml::submitButton(Yii::t('UserModule.views_auth_login', 'Register'), array('class' => 'btn btn-primary')); ?>
               </div>

                <hr>
                <div class="row">
                    <div class="col-md-12 text-left">
                        <small>
                            <?php echo Yii::t('UserModule.views_auth_signup', 'Already have an account?'); ?>
                            <a href="<?php echo Url::toRoute('/user/auth/login'); ?>"><?php echo Yii::t('UserModule.views_auth_signup', 'Login') ?></a>
                        </small>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <?php endif; ?>

    <?= humhub\widgets\LanguageChooser::widget(); ?>
</div>

<script type="text/javascript">
    $(function () {
        // set cursor to login field
        $('#login_username').focus();
    })



    // Shake panel after wrong validation
    <?php if ($registerModel->hasErrors()) { ?>
    $('#register-form').removeClass('bounceInLeft');
    $('#register-form').addClass('shake');
    $('#login-form').removeClass('bounceIn');
    $('#app-title').removeClass('fadeIn');
    <?php } ?>

</script>


