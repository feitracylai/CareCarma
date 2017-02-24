<?php

use yii\helpers\Html;
use humhub\models\Setting;
?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('AdminModule.views_setting_cronjob', '<strong>CronJob</strong> settings'); ?></div>
    <div class="panel-body">

        <?php
        $currentUser = '';
        if (function_exists('get_current_user')) {
            $currentUser = get_current_user();
        }
        ?>

        <p>
            <strong>Status:</strong><br/>
            <?php
            $lastRunHourly = Setting::get('cronLastHourlyRun');
            $lastRunDaily = Setting::get('cronLastDailyRun');

            if ($lastRunHourly == "") {
                $lastRunHourly = "<span style='color:red'>" . Yii::t('AdminModule.views_setting_cronjob', 'Never') . "</span>";
            } else {
                $lastRunHourly = \humhub\widgets\TimeAgo::widget(['timestamp' => $lastRunHourly]);
            }
            if ($lastRunDaily == "") {
                $lastRunDaily = "<span style='color:red'>" . Yii::t('AdminModule.views_setting_cronjob', 'Never') . "</span>";
            } else {
                $lastRunDaily = \humhub\widgets\TimeAgo::widget(['timestamp' => $lastRunDaily]);            }
            ?>

            <?php echo Yii::t('AdminModule.views_setting_cronjob', 'Last run (hourly):'); ?> <?php echo $lastRunHourly; ?> <br/>
            <?php echo Yii::t('AdminModule.views_setting_cronjob', 'Last run (daily):'); ?> <?php echo $lastRunDaily; ?>
        </p>

        <p><?php echo Yii::t('AdminModule.views_setting_cronjob', 'Please make sure following cronjobs are installed:'); ?></p>
        <pre>

<strong><?php echo Yii::t('AdminModule.views_setting_cronjob', 'Crontab of user: {user}', array('{user}' => $currentUser)); ?></strong>
30 * * * * <?php echo Yii::getAlias('@app/yii'); ?> cron/hourly >/dev/null 2>&1
00 22 * * * <?php echo Yii::getAlias('@app/yii'); ?> cron/daily >/dev/null 2>&1

            <?php if ($currentUser != ""): ?>

<strong><?php echo Yii::t('AdminModule.views_setting_cronjob', 'Or Crontab of root user'); ?></strong>
*/5 * * * * su -c "<?php echo Yii::getAlias('@app/yii'); ?>  cron/hourly" <?php echo $currentUser; ?>
    >/dev/null 2>&1
0 22 * * * su -c "<?php echo Yii::getAlias('@app/yii'); ?>  cron/daily" <?php echo $currentUser; ?> >/dev/null 2>&1
<?php endif; ?>
        </pre>

    </div>
</div>
