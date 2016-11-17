<?php

use yii\helpers\Html;
use yii\log\Logger;


    \Yii::getLogger()->log($originator->displayName, Logger::LEVEL_INFO, 'MyLog');
    echo Yii::t('LikeModule.views_notifications_newLike', "%displayName% likes %contentTitle%.", array(
        '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
        '%contentTitle%' => $this->context->getContentInfo($source->getSource())
    ));



?>
