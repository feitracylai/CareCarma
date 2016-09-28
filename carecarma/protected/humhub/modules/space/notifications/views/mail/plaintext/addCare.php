<?php

use yii\helpers\Html;

echo strip_tags(Yii::t('SpaceModule.views_notifications_invite', '{userName} add you as an Care Receiver in circle {spaceName}. If you accept it, all the administrators in {spaceName} can manage your account.', array(
    '{userName}' => Html::encode($originator->displayName),
    '{spaceName}' => Html::encode($source->name)
)));
?>