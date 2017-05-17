<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/24/2017
 * Time: 11:04 AM
 */
use humhub\compat\CActiveForm;

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {

    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Time: " + (index + 1));
        
    });
    

    

});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Time: " + (index + 1));
    });
    

});
';

$this->registerJs($js);

$this->registerJs(' 
$(function () {
    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $( ".reminder-time-datepicker" ).each(function() {
           $( this ).datepicker({dateFormat : "M d, yy"});
      });
      
        
    });
});
$(function () {
    $(".dynamicform_wrapper").on("afterDelete", function(e, item) {
        $( ".reminder-time-datepicker" ).each(function() {
           $( this ).removeClass("hasDatepicker").datepicker({dateFormat : "M d, yy"});
      });          
    });
});
');
$index = 0;
?>

<div class="modal-dialog modal-dialog-normal animated fadeIn" xmlns="http://www.w3.org/1999/html">
    <div class="modal-content">
        <?php $form = CActiveForm::begin(['id' => 'dynamic-form']); ?>

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t('ReminderModule.views_receiver_add', '<strong>Add</strong> reminder'); ?></h4>
        </div>

        <div class="modal-body">
            <?php echo $form->field($reminder, 'title')->textarea(['id' => 'itemTitle', 'class' => 'form-control autosize', 'rows' => '1', 'placeholder' => Yii::t('ReminderModule.views_receiver_add', 'Remind about...')]); ?>

            <div class="padding-v-md">
                <div class="line line-dashed"></div>
            </div>
                <?php \wbraganca\dynamicform\DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 4, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $reminder_times[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'time',
                        'repeat',
                        'date',
                        'day',

                    ],
                ]);?>


                <div class="panel-body container-items"><!-- widgetContainer -->
                    <?php foreach ($reminder_times as $reminder_time): ?>
                        <div class="item panel panel-default"><!-- widgetBody -->
                            <div class="panel-heading">

                                <span class="panel-title-address" >Time: <?= ($index + 1) ?></span>

                                <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <?php
                                // necessary for update action.
                                if (!$reminder_time->isNewRecord) {
                                    echo \yii\helpers\Html::activeHiddenInput($reminder_time, "[{$index}]id");
                                }
                                ?>

                                <div class="row">
                                    <div class="col-md-5">
                                        <?php echo $form->field($reminder_time, "[{$index}]time")->widget(\kartik\time\TimePicker::className(), [
                                            'options' => ['class' => 'form-control'],
                                            'pluginOptions' => ['minuteStep' => 1, 'showMeridian' => false]
                                        ]); ?>

                                    </div>
                                    <div class="col-md-2">
                                        <label>

                                            <?php  echo $form->field($reminder_time, "[{$index}]repeat")->checkbox(['label' => '', 'onclick' => 'return OptionsSelected(this)'])->label('Repeat'); ?>
                                        </label>


                                    </div>

                                    <div class="col-md-5">
                                            <?php echo $form->field($reminder_time, "[{$index}]day", ['options' => ['style' => 'display : none']])->dropDownList([
                                                'everyday' => 'Everyday',
                                                'Sun' => 'Every Sunday',
                                                'Mon' => 'Every Monday',
                                                'Tue' => 'Every Tuesday',
                                                'Wed' => 'Every Wednesday',
                                                'Thur' => 'Every Thursday',
                                                'Fri' => 'Every Friday',
                                                'Sat' => 'Every Saturday'
                                            ], ['prompt' => 'Please select:']);
                                            ?>

                                            <?php echo $form->field($reminder_time, "[{$index}]date")->widget(\yii\jui\DatePicker::className(), ['options' => ['class' => 'form-control reminder-time-datepicker']]); ?>



                                    </div>
                                </div><!-- end:row -->
                            </div>
                        </div>
                    <?php $index++; ?>
                    <?php endforeach; ?>
                </div>

            <div class="panel-heading">
                <button type="button" class="pull-left add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add time</button>
                <div class="clearfix"></div>
            </div>
                <?php \wbraganca\dynamicform\DynamicFormWidget::end(); ?>

<!--            </div>-->

            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo \humhub\widgets\AjaxButton::widget([
                        'label' => Yii::t('TasksModule.views_task_edit', 'Send'),
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                            'success' => new yii\web\JsExpression('function(html){ $("#globalModal").html(html); }'),
                            'url' => $space->createUrl('add', ['rguid' => $receiver->guid]),
                        ],
                        'htmlOptions' => [
                            'class' => 'btn btn-primary'
                        ]
                    ]);
                    ?>

                    <button type="button" class="btn btn-primary"
                            data-dismiss="modal"><?php echo Yii::t('TasksModule.views_task_edit', 'Cancel'); ?></button>
                </div>
            </div>

        </div>

        <?php CActiveForm::end(); ?>
    </div>
</div>

<script type="text/javascript">






    function OptionsSelected(me)
    {
        var id = me.id.charAt(19);
        if ($(me).prop('checked')){
//            alert(id);
            $(".field-reminderdevicetime-"+ id + "-day").show();
            $(".field-reminderdevicetime-"+ id + "-date").hide();
        } else {
            $(".field-reminderdevicetime-"+ id + "-date").show();
            $(".field-reminderdevicetime-"+ id + "-day").hide();
        }


    }

    $("#repeatCheckbox").change(function () {
        if ($("#repeatCheckbox").prop('checked')) {
            $('#repeatBox').show();
            $("#dateBox").hide();
        } else {
            $('#repeatBox').hide();
            $("#dateBox").show();
        }
    });

    if ($("#repeatCheckbox").prop('checked')) {
        $('#repeatBox').show();
        $("#dateBox").hide();
    } else {
        $('#repeatBox').hide();
        $("#dateBox").show();
    }



</script>

<style>
    .multiselect-container{
        min-width: 170px;
    }
</style>