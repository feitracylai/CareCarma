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

//Yii::getLogger()->log(print_r($device_show->space_id, true), \yii\log\Logger::LEVEL_INFO, 'MyLog');
$user = User::findOne(['id' =>  $device_show->report_user_id]);
?>

<?php if (empty($device_show->space_id)): ?>
    <li class="userPreviewEntry_<?php echo $user->id; ?> userPreviewEntry entry <?php if ($device_show->seen == 0): ?>new<?php endif; ?>" >
        <a href="<?php echo $user->createUrl('/devices/view/index') ?>">
            <div class="media">

                <img class="media-object img-rounded pull-left" data-src="holder.js/32x32" alt="32x32" style="
            width: 32px; height: 32px;" src="<?php echo $user->getProfileImage()->getUrl(); ?>">

                <div class="media-body">
                    <h4 class="media-heading">
                        <?php echo Html::encode($user->displayName); ?>

                    </h4>
                    <!--                --><?php //echo Yii::t('DevicesModule.views_report_index', '3304 steps'); ?>

                    <?php echo TimeAgo::widget(['timestamp' => $device_show->updated_at]); ?>
                    <?php
                    // show the new badge, if this report is still unread
                    if ($device_show->seen == 0) {
                        echo '<span class="label label-danger">' . Yii::t('DevicesModule.views_report_index', 'New') . '</span>';
                    }
                    ?>
                </div>

            </div>
        </a>

    </li>

<?php else: $space = Space::findOne(['id' => $device_show->space_id]); ?>
<li class="userPreviewEntry_<?php echo $user->id; ?> userPreviewEntry entry <?php if ($device_show->seen == 0): ?>new<?php endif; ?>" >
    <a href="<?php echo $space->createUrl('/space/manage/device/report',['rguid' => $user->guid]) ?>">
        <div class="media">

            <img class="media-object img-rounded pull-left" data-src="holder.js/32x32" alt="32x32" style="
            width: 32px; height: 32px;" src="<?php echo $user->getProfileImage()->getUrl(); ?>">
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

                <?php echo TimeAgo::widget(['timestamp' => $device_show->updated_at]); ?>
                <?php
                // show the new badge, if this report is still unread
                if ($device_show->seen == 0) {
                    echo '<span class="label label-danger">' . Yii::t('DevicesModule.views_report_index', 'New') . '</span>';
                }
                ?>
            </div>

        </div>
    </a>

</li>
<?php endif; ?>