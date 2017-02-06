<?php


use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\Device;

$hasCR = false;
?>

    <?php foreach (Membership::GetUserSpaces() as $space) : ?>

        <?php
        $space_members = Membership::find()->where(['space_id' => $space->id, 'group_id' => Space::USERGROUP_MODERATOR])->andWhere(['<>','user_id', Yii::$app->user->id])->all();
        if (count($space_members) != 0){
            foreach ($space_members as $space_member){
//                if ($space_member->user_id == )
                $dataDevices = Device::find()->where(['user_id' => $space_member->user_id, 'activate' => 1])->andWhere(['<>','type', 'phone'])->all();
                if ($dataDevices){
                    $hasCR = true;
                    $isNew = false;
                    echo $this->render('_receiverPreview', array('userId' => $space_member->user_id, 'spaceId' => $space_member->space_id, 'isNew' => $isNew, 'devices' => $dataDevices));
                }

            }
        }

        ?>
    <?php endforeach; ?>



<?php if (!$hasCR): ?>
    <li class="placeholder"> <?php echo Yii::t('DevicesModule.views_report_list', 'There are no receiver in your circles now.'); ?></li>
<?php endif; ?>