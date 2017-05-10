<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:14 PM
 */

use humhub\widgets\NotificationArea;
use humhub\modules\user\widgets\ProfileMenu;
use humhub\modules\devices\Events;
use humhub\commands\CronController;

return [
    'id' => 'devices',
    'class' => \humhub\modules\devices\Module::className(),
    'namespace' => 'humhub\modules\devices',
    'events' => [
        array('class' => NotificationArea::className(), 'event' => NotificationArea::EVENT_INIT, 'callback' => array('humhub\modules\devices\Events', 'onNotificationAddonInit')),
        array('class' => ProfileMenu::className(), 'event' => ProfileMenu::EVENT_INIT, 'callback' => array('humhub\modules\devices\Events', 'onProfileMenuInit')),
        array('class' => CronController::className(), 'event' => CronController::EVENT_ON_HOURLY_RUN, 'callback' => array(Events::className(), 'onCronRun')),
    ]
];

?>