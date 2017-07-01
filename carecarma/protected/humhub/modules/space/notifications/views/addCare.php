<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/26/2016
 * Time: 1:47 PM
 */

use yii\helpers\Html;

echo Yii::t('SpaceModule.views_notifications_addCare', '{userName} add you as an Care Receiver in circle {spaceName}.', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{spaceName}' => '<strong>' . Html::encode($source->name) . '</strong>'
));
?>