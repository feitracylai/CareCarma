<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:14 PM
 */

use humhub\widgets\NotificationArea;


return [
    'id' => 'devices',
    'class' => \humhub\modules\devices\Module::className(),
    'namespace' => 'humhub\modules\devices',
    'events' => [
        array('class' => NotificationArea::className(), 'event' => NotificationArea::EVENT_INIT, 'callback' => array('humhub\modules\devices\Events', 'onNotificationAddonInit')),
    ]
];

?>