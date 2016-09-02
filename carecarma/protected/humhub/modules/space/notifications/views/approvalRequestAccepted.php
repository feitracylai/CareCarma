<?php

use yii\helpers\Html;

echo Yii::t('SpaceModule.views_notifications_approvalRequestAccepted', '{userName} approved your membership for the circle {spaceName}', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{spaceName}' => '<strong>' . Html::encode($source->name) . '</strong>'
));
?>