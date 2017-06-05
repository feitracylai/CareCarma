<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:14 PM
 */

use humhub\widgets\NotificationArea;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
use humhub\commands\CronController;
//use humhub\widgets\TopMenu;

return [
    'id' => 'reminder',
    'class' => \humhub\modules\reminder\Module::className(),
    'namespace' => 'humhub\modules\reminder',
    'events' => [
        array('class' => NotificationArea::className(), 'event' => NotificationArea::EVENT_INIT, 'callback' => array('humhub\modules\reminder\Events', 'onNotificationAddonInit')),
        array('class' => CareEditMenu::className(), 'event' => CareEditMenu::EVENT_INIT, 'callback' => array('humhub\modules\reminder\Events', 'setCareRemind')),
//        array('class' => TopMenu::className(), 'event' => TopMenu::EVENT_INIT, 'callback' => array('humhub\modules\reminder\Events', 'onTopMenuInit')),
        array('class' => CronController::className(), 'event' => CronController::EVENT_ON_DAILY_RUN, 'callback' => array('humhub\modules\reminder\Events', 'onCronRun')),
    ]
];

?>