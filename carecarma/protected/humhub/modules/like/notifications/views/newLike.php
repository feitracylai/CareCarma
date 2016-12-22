<?php

use yii\helpers\Html;


if ($source == null) {
    echo Yii::t('LikeModule.views_notifications_newLike', "%displayName% likes your comment.", array(
        '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    ));
} else {
    echo Yii::t('LikeModule.views_notifications_newLike', "%displayName% likes %contentTitle%.", array(
        '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
        '%contentTitle%' => $this->context->getContentInfo($source->getSource())
    ));
}



?>
