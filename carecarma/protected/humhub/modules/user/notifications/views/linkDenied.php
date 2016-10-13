<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/8/2016
 * Time: 6:21 PM
 */

use yii\helpers\Html;

echo Yii::t('UserModule.views_notifications_linkDenied', '{userName} denied your invite for your network contacts', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>'
));
?>