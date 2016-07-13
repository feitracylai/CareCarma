<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/13/2016
 * Time: 11:40 AM
 */

use yii\helpers\Html;

echo strip_tags(Yii::t('UserModule.views_notifications_linkRemove', '{userName} remove your contacts link', array(
    '{userName}' => Html::encode($originator->displayName)
)));
?>