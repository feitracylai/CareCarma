<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/13/2016
 * Time: 11:40 AM
 */

use yii\helpers\Html;

echo Yii::t('UserModule.views_notifications_linkRemove', '{userName} remove you in his/her PEOPLE', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>'
));
?>