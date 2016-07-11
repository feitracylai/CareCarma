<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/8/2016
 * Time: 6:11 PM
 */

use yii\helpers\Html;

echo Yii::t('UserModule.views_notifications_linkAccepted', '{userName} accepted your link to your contacts', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>'
));
?>