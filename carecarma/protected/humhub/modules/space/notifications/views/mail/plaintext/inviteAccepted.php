<?php

use yii\helpers\Html;

echo strip_tags(Yii::t('SpaceModule.views_notifications_inviteAccepted', '{userName} accepted your invite for the family {spaceName}', array(
    '{userName}' => Html::encode($originator->displayName),
    '{spaceName}' => Html::encode($source->name)
)));
?>