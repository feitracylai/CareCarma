<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/13/2017
 * Time: 6:00 PM
 */

use humhub\modules\user\models\User;
use humhub\modules\space\models\Space;
use yii\helpers\Html;
use humhub\widgets\TimeAgo;

$space = Space::findOne(['id' => $spaceId]);
$user = User::findOne(['id' => $userId]);

$reportTime = array();
$stepNew = false;
$heartrateNew = false;
foreach ($devices as $device){
    $lastReport = \humhub\modules\devices\models\Classlabelshoursteps::find()->where(['hardware_id' => $device->hardware_id])->orderBy('updated_at DESC')->one();
    if ($lastReport){
        $reportTime[] = $lastReport->updated_at;
        //the report just show one week data
        if ($lastReport->time > $start_time && $lastReport->seen == 0)$stepNew = true;
    }


    $lastHeartrate = \humhub\modules\devices\models\Classlabelshourheart::find()->where(['hardware_id' => $device->hardware_id])->orderBy('updated_at DESC')->one();
    if ($lastHeartrate){
        $reportTime[] = $lastHeartrate->updated_at;
        if ($lastHeartrate->time > $start_time && $lastHeartrate->seen == 0)$heartrateNew = true;
    }

}
if (!empty($lastReport))
    $lastReportTime = max($reportTime);


?>
<!--<li class="userPreviewEntry_--><?php //echo $user->id; ?><!-- userPreviewEntry entry --><?php //if ($heartrateNew || $stepNew) : ?><!--new--><?php //endif; ?><!--" >-->
<li class="userPreviewEntry_<?php echo $user->id; ?> userPreviewEntry entry " >
    <a href="<?php echo $space->createUrl('/space/manage/device/report',['rguid' => $user->guid]) ?>">
        <div class="media">

            <img class="media-object img-rounded pull-left" data-src="holder.js/32x32" alt="32x32" style="width: 32px; height: 32px;" src="<?php echo $user->getProfileImage()->getUrl(); ?>">
            <?php echo \humhub\modules\space\widgets\Image::widget([
                'space' => $space,
                'width' => 20,
                'htmlOptions' => [
                    'class' => 'img-space',
                ],
            ]); ?>

            <div class="media-body">
                <h4 class="media-heading">
                    <?php echo Html::encode($user->displayName); ?>
                    <small>
                        <?php echo Yii::t('DevicesModule.views_report_index', 'in'); ?> <strong><?php echo Html::encode($space->name); ?></strong>
                    </small>
                </h4>
<!--                --><?php //echo Yii::t('DevicesModule.views_report_index', '3304 steps'); ?>

                <?php if (!empty($lastReport)) echo TimeAgo::widget(['timestamp' => $lastReportTime]); ?>
<!--                --><?php
//                // show the new badge, if this report is still unread
//                if ($heartrateNew || $stepNew) {
//                    echo '<span class="label label-danger">' . Yii::t('DevicesModule.views_report_index', 'New') . '</span>';
//                }
//                ?>
            </div>

        </div>
    </a>

</li>
