<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/12/2016
 * Time: 5:22 PM
 */

use yii\helpers\Html;

echo Yii::t('UserModule.views_notifications_link', '{userName} add you to PEOPLE.', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
));
?>