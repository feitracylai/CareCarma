<?php

use yii\helpers\Html;

echo strip_tags(Yii::t('SpaceModule.views_notifications_inviteDeclined', '{userName} declined your invite for the circle {spaceName}', array(
    '{userName}' => Html::encode($originator->displayName),
    '{spaceName}' => Html::encode($source->name)
)));
?>
