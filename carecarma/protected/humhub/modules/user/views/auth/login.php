<?php

use \yii\helpers\Url;
use yii\widgets\ActiveForm;
use \humhub\compat\CHtml;

$this->pageTitle = Yii::t('UserModule.views_auth_login', 'Login');
?>


<div class="container" style="text-align: center;">



    <div class="panel panel-default animated bounceIn" id="login-form"
         style="max-width: 300px; margin: 0 auto 20px; text-align: left;">

        <div class="panel-heading"><?php echo Yii::t('UserModule.views_auth_login', '<strong>Please</strong> sign in'); ?></div>

        <div class="panel-body">

            <?php $form = ActiveForm::begin(['id' => 'account-login-form']); ?>

            <p><?php echo Yii::t('UserModule.views_auth_login', "If you're already a member, please login with your username/email and password."); ?></p>

            <?php echo $form->field($model, 'username')->textInput(['id' => 'login_username', 'placeholder' => $model->getAttributeLabel('username')])->label(false); ?>
            <?php echo $form->field($model, 'password')->passwordInput(['id' => 'login_password', 'placeholder' => $model->getAttributeLabel('password')])->label(false); ?>
            <?php echo $form->field($model, 'rememberMe')->checkbox(); ?>

            <hr>
            <div class="row">
                <div class="col-md-4">
                    <?php echo CHtml::submitButton(Yii::t('UserModule.views_auth_login', 'Sign in'), array('id' => 'login-button', 'class' => 'btn btn-large btn-primary')); ?>
                </div>
                <div class="col-md-8 text-right">
                    <small>
                        <?php echo Yii::t('UserModule.views_auth_login', 'Forgot your password?'); ?>
                        <a
                            href="<?php echo Url::toRoute('/user/auth/recover-password'); ?>"><br><?php echo Yii::t('UserModule.views_auth_login', 'Create a new one.') ?></a>
                    </small>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-8 text-left">
                    <small>
                        <?php echo Yii::t('UserModule.views_auth_login', 'Do not have an account?'); ?>
                        <a
                            href="<?php echo Url::toRoute('/user/auth/signup'); ?>"><br><?php echo Yii::t('UserModule.views_auth_login', 'Sign up') ?></a>
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


