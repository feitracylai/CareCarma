<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/6/2016
 * Time: 4:56 PM
 */

use yii\helpers\Html;

echo strip_tags(Yii::t('UserModule.views_notifications_link', '{userName} want to link you to his/her contacts.', array(
    '{userName}' => Html::encode($originator->displayName),
)));
?>