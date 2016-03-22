<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('UserModule.views_contact_add', '<strong>Add</strong> contact'); ?></div>
    <div class="panel-body">
        <?=\humhub\modules\user\widgets\ContactMenu::widget(); ?>
        <p />

<!--        --><?php //echo Html::beginForm(Url::to(['/user/contact/add']), 'get', array('class' => 'form-search')); ?>
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="form-group form-group-search">
<!--                    --><?php //echo Html::textInput("keyword", $keyword, array("class" => "form-control form-search")); ?>
<!--                    --><?php //echo Html::submitButton(Yii::t('UserModule.views_contact_add', 'Search'), array('class' => 'btn btn-default btn-sm form-button-search')); ?>
                    <?php echo $form->field($model, 'invite')->textInput(['id' => 'invite'])->label(false); ?>

                    <?php
                    // attach mention widget to it
                    echo humhub\modules\user\widgets\UserPicker::widget(array(
                        'inputId' => 'invite',
                        'model' => $model, // CForm Instanz
                        'attribute' => 'invite',
//                        'placeholderText' => Yii::t('UserModule.views_contact_add', 'Add an user'),
                        'focus' => true,
                    ));
                    ?>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
        <!--        --><?php //echo Html::endForm(); ?>

        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>

    </div>
</div>