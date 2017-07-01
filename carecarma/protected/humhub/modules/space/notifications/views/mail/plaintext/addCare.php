<?php

use yii\helpers\Html;

echo strip_tags(Yii::t('SpaceModule.views_notifications_invite', '{userName} add you as an Care Receiver in circle {spaceName}.', array(
    '{userName}' => Html::encode($originator->displayName),
    '{spaceName}' => Html::encode($source->name)
)));
?>