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


//use notification created_at time to test Time Show
$testNotification = \humhub\modules\notification\models\Notification::find()->orderBy('notification.created_at DESC')->limit(1)->one();

foreach ($devices as $device){
    $lastReport = \humhub\modules\devices\models\Classlabelshoursteps::find()->where(['hardware_id' => $device->hardware_id])->orderBy('updated_at DESC')->one();
}


?>

<li class="userPreviewEntry_<?php echo $user->id; ?> userPreviewEntry entry <?php if ($isNew) : ?>new<?php endif; ?>" >
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
                <br><?php echo TimeAgo::widget(['timestamp' => $lastReport->updated_at]); ?>
            </div>

        </div>
    </a>

</li>
