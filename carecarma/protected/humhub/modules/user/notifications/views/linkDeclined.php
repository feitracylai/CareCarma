<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/8/2016
 * Time: 6:21 PM
 */

use yii\helpers\Html;

echo Yii::t('UserModule.views_notifications_linkDeclined', '{userName} declined link to your contacts', array(
    '{userName}' => '<strong>' . Html::encode($source->displayName) . '</strong>'
));
?>