<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/26/2016
 * Time: 2:55 PM
 */

use yii\helpers\Html;

echo strip_tags(Yii::t('SpaceModule.views_notifications_inviteDeclined', '{userName} declined your Care Receiver adding for the circle {spaceName}', array(
    '{userName}' => Html::encode($originator->displayName),
    '{spaceName}' => Html::encode($source->name)
)));
?>