<?php

use yii\helpers\Html;

echo Yii::t('SpaceModule.views_notifications_approvalRequestDeclined', '{userName} declined your membership request for the circle {spaceName}', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{spaceName}' => '<strong>' . Html::encode($source->name) . '</strong>'
));
?>
