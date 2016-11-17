<?php

use yii\helpers\Html;
use yii\log\Logger;

if (!isset($source->getSource) || $source->getSource() == null){
    \Yii::getLogger()->log($originator->displayName, Logger::LEVEL_INFO, 'MyLog');
} else{
    \Yii::getLogger()->log($originator->displayName, Logger::LEVEL_INFO, 'MyLog');
    echo Yii::t('LikeModule.views_notifications_newLike', "%displayName% likes %contentTitle%.", array(
        '%displayName%' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
        '%contentTitle%' => $this->context->getContentInfo($source->getSource())
    ));
}


?>
