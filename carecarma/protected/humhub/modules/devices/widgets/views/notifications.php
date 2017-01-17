<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:54 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;


?>

<div class="btn-group">
    <a href="#" id="icon-report" class="dropdown-toggle" data-toggle="dropdown"><i
            class="fa fa-file"></i></a>
    <span id="badge-report" style="display:none;"
          class="label label-danger label-notification">1</span>
    <ul id="dropdown-report" class="dropdown-menu">
    </ul>
</div>

<script type="text/javascript">
    // open the report menu
    $('#icon-report').click(function () {

        // remove all <li> entries from dropdown
        $('#dropdown-report').find('li').remove();
        $('#dropdown-report').find('ul').remove();

        // append title and loader to dropdown
        $('#dropdown-report').append('<li class="dropdown-header"><div class="arrow"></div><?php echo Yii::t('DevicesModule.widgets_views_mailNotification', 'Report Lists'); ?></li><ul class="media-list"><li id="loader_reports"><div class="loader"></div></li></ul>');


        $.ajax({
            'type': 'GET',
            'url': '<?php echo Url::to(['/devices/report/report-list']); ?>',
            'cache': false,
            'data': jQuery(this).parents("form").serialize(),
            'success': function (html) {
                jQuery("#loader_reports").replaceWith(html)
            }});
    })
</script>

<!--<li class="dropdown-header">-->
<!--    <div class="arrow"></div>-->
<!--    --><?php //echo Yii::t('DevicesModule.widgets_views_mailNotification', 'Report Lists'); ?>
<!--    --><?php //echo Html::a(Yii::t('DevicesModule.widgets_views_mailNotification', 'Show my report'), Url::to(['/mail/mail/create', 'ajax' => 1]), array('class' => 'btn btn-info btn-xs', 'id' => 'create-message-button', 'data-target' => '#globalModal')); ?>
<!--</li>-->
<!--<ul class="media-list">-->
<!--    <li id="loader_messages">-->
<!--        <div class="loader"></div>-->
<!--    </li>-->
<!--</ul>-->



