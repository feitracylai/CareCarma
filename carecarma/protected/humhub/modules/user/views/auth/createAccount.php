<?php

use yii\helpers\Html;

$this->pageTitle = Yii::t('UserModule.views_auth_createAccount', 'Create Account');
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
        <div id="create-account-form" class="panel panel-default animated bounceIn" style="max-width: 500px; margin: 0 auto 20px; text-align: left;">
            <div class="panel-heading" style="text-align: center"><?php echo Yii::t('UserModule.views_auth_createAccount', '<strong>Create</strong> an account'); ?></div>
            <div class="panel-body">
                <?php $form = \yii\widgets\ActiveForm::begin(['enableClientValidation' => false]); ?>
                <?php echo $hForm->render($form); ?>
                <?php \yii\widgets\ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        // set cursor to login field
        $('#User_username').focus();
    })

    // Shake panel after wrong validation
<?php foreach ($hForm->models as $model) : ?>
    <?php if ($model->hasErrors()) : ?>
            $('#create-account-form').removeClass('bounceIn');
            $('#create-account-form').addClass('shake');
            $('#app-title').removeClass('fadeIn');
    <?php endif; ?>
<?php endforeach; ?>

</script>
