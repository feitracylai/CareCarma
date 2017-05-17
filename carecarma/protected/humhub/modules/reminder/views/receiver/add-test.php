<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/24/2017
 * Time: 11:04 AM
 */
use humhub\compat\CActiveForm;
?>

<div class="modal-dialog modal-dialog-normal animated fadeIn" xmlns="http://www.w3.org/1999/html">
    <div class="modal-content">
        <?php $form = CActiveForm::begin(); ?>

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t('ReminderModule.views_receiver_add', '<strong>Add</strong> reminder'); ?></h4>
        </div>

        <div class="modal-body">
            <?php echo $form->field($reminder, 'title')->textarea(['id' => 'itemTitle', 'class' => 'form-control autosize', 'rows' => '1', 'placeholder' => Yii::t('ReminderModule.views_receiver_add', 'Remind about...')]); ?>

            <div class="row">
                <div class="col-md-5">
                    <?php echo $form->field($reminder_time, 'time')->widget(\kartik\time\TimePicker::className(), [
                        'options' => ['class' => 'form-control'],
                        'pluginOptions' => ['minuteStep' => 1, 'showMeridian' => false]
                    ]); ?>

                </div>
                <div class="col-md-2">
                    <label>
                        <br><br>
                        <?php echo $form->checkBox($reminder_time, 'repeat', [ 'label' => 'Repeat', 'id' => 'repeatCheckbox']); ?>
                    </label>


                </div>

                <div class="col-md-5">
                    <div id="repeatBox">
                        <?php echo $form->field($reminder_time, 'repeat_days')->widget(\dosamigos\multiselect\MultiSelect::className(), [
                            "options" => [
                                'multiple'=>"multiple",
                            ], // for the actual multiselect
                            'data' => [
                                'Sun' => 'Every Sunday',
                                'Mon' => 'Every Monday',
                                'Tue' => 'Every Tuesday',
                                'Wed' => 'Every Wednesday',
                                'Thur' => 'Every Thursday',
                                'Fri' => 'Every Friday',
                                'Sat' => 'Every Saturday'
                            ], // data as array
//                            'attribute' => 'repeat_date', // if preselected
//                            'model' =>  $reminder, // name for the form
                            "clientOptions" =>
                                [
//                                    "includeSelectAllOption" => true,
                                    'numberDisplayed' => 1,
                                ],
                        ]);?>
<!--                        --><?php //echo \dosamigos\multiselect\MultiSelect::widget([
//                            "options" => [
//                                'multiple'=>"multiple",
////                                'method' => 'POST',
//                            ], // for the actual multiselect
//                            'data' => [
//                                'Sun' => 'Every Sunday',
//                                'Mon' => 'Every Monday',
//                                'Tue' => 'Every Tuesday',
//                                'Wed' => 'Every Wednesday',
//                                'Thur' => 'Every Thursday',
//                                'Fri' => 'Every Friday',
//                                'Sat' => 'Every Saturday'
//                            ], // data as array
//                            'attribute' => 'repeat_date', // if preselected
//                            'model' =>  $reminder, // name for the form
//                            "clientOptions" =>
//                                [
////                                    "includeSelectAllOption" => true,
//                                    'numberDisplayed' => 2
//                                ],
//                        ]);?>

                    </div>
                    <div id="dateBox">
                        <?php echo $form->field($reminder_time, 'date')->widget(\yii\jui\DatePicker::className(), ['options' => ['class' => 'form-control']]); ?>

                    </div>


                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo \humhub\widgets\AjaxButton::widget([
                        'label' => Yii::t('TasksModule.views_task_edit', 'Send'),
                        'ajaxOptions' => [
                            'type' => 'POST',
                            'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                            'success' => new yii\web\JsExpression('function(html){ $("#globalModal").html(html); }'),
                            'url' => $space->createUrl('add-test', ['rguid' => $receiver->guid]),
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

    $('.autosize').autosize();

//    $(document).ready(function () {
//        var myInterval = setInterval(function () {
//            $('#itemTitle).focus();
//            clearInterval(myInterval);
//        }, 100);
//    });


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