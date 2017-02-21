<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:54 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;

$user = \humhub\modules\user\models\User::findOne(['id' => Yii::$app->user->id]);
$devices = \humhub\modules\user\models\Device::find()->where(['user_id' => $user->id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->one();
?>

<div class="btn-group">
    <a href="#" id="icon-report" class="dropdown-toggle" data-toggle="dropdown"><i
            class="fa fa-bar-chart"></i></a>
    <span id="badge-report" style="display:none;"
          class="label label-danger label-notification">1</span>
    <ul id="dropdown-report" class="dropdown-menu">
    </ul>
</div>

<script type="text/javascript">

    /**
     * Refresh New Report Count (Badge)
     */
    reloadReportCountInterval = 60000;
    setInterval(function () {
        jQuery.getJSON("<?php echo Url::to(['/devices/report/get-new-report-count-json']); ?>", function (json) {
            setNewReportCount(parseInt(json.newReport));
        });
    }, reloadReportCountInterval);

    setNewReportCount(<?php echo $newReportCount; ?>);


    /**
     * Sets current message count
     */
    function setNewReportCount(count) {
        // show or hide the badge for new messages
        if (count == 0) {
            $('#badge-report').css('display', 'none');
        } else {
            $('#badge-report').empty();
            $('#badge-report').append(count);
            $('#badge-report').fadeIn('fast');
        }
    }

    // open the report menu
    $('#icon-report').click(function () {

        // remove all <li> entries from dropdown
        $('#dropdown-report').find('li').remove();
        $('#dropdown-report').find('ul').remove();

        // append title and loader to dropdown
        $('#dropdown-report').append('<li class="dropdown-header"><div class="arrow"></div><?php echo Yii::t('DevicesModule.widgets_views_mailNotification', 'Report Lists'); ?> <?php if ($devices) echo Html::a(Yii::t('MailModule.widgets_views_mailNotification', 'My Report'), $user->createUrl('/devices/view/index'), array('class' => 'btn btn-info btn-xs', 'id' => 'create-message-button')); ?></li><ul class="media-list"><li id="loader_reports"><div class="loader"><div class="sk-spinner sk-spinner-three-bounce"><div class="sk-bounce1"></div><div class="sk-bounce2"></div><div class="sk-bounce3"></div></div></div></li></ul>');


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





