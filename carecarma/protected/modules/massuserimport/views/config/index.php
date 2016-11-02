<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use humhub\modules\massuserimport\Assets;

Assets::register($this);
?>

<div class="panel panel-default">
    <div class="massuserimport-header">
        <h1><?php echo Yii::t('MassuserimportModule.base', 'Mass User Import Module Configuration'); ?></h1>
    </div>
    <div class="panel-body">
        <p><?php echo Yii::t('MassuserimportModule.base', 'Configure the mass user import module here'); ?></p>
        <?php
        $form = ActiveForm::begin([
            'enableAjaxValidation' => false
        ]);
        $form->setId('configure-form');
        ?>
 	<div>
		<?= $form->field($model, 'activateJsonRestApi')->textInput()->label(Yii::t('MassuserimportModule.base', 'Activate/deactivate json rest API.'));?>
		<?= $form->field($model, 'jsonRestApiPassword')->textInput()->label(Yii::t('MassuserimportModule.base', 'Password to access the json API.'));?>
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary'])?>
    </div>
	<?php ActiveForm::end(); ?>
    </div>
</div>
