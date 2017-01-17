<?php


use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;


?>

<?php if (count($space_list) != 0) : ?>
    <?php foreach ($space_list as $listSpaceId) : ?>

        <?php
        $space_members = Membership::find()->where(['space_id' => $listSpaceId, 'group_id' => Space::USERGROUP_MODERATOR])->andWhere(['<>','user_id', Yii::$app->user->id])->all();
        if (count($space_members) != 0){
            foreach ($space_members as $space_member){
//                if ($space_member->user_id == )
                $isNew = false;
                echo $this->render('_receiverPreview', array('userId' => $space_member->user_id, 'spaceId' => $space_member->space_id, 'isNew' => $isNew));
            }
        }

        ?>
    <?php endforeach; ?>
<?php else: ?>
    <li class="placeholder"> <?php echo Yii::t('DevicesModule.views_report_list', 'There are no receiver in your circles now.'); ?></li>
<?php endif; ?>