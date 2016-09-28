<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/26/2016
 * Time: 2:54 PM
 */

use yii\helpers\Html;

echo Yii::t('SpaceModule.views_notifications_inviteDeclined', '{userName} declined your Care Receiver adding for the circle {spaceName}', array(
//    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{userName}' => '<strong>' . Html::encode($source->displayName) . '</strong>',
    '{spaceName}' => '<strong>' . Html::encode($source->name) . '</strong>'
));
?>