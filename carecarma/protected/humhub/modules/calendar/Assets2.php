<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\calendar;

use yii\web\AssetBundle;

class Assets2 extends AssetBundle
{

    public $css = [
        'fullcalendar/fullcalendar.css',
        //'fullcalendar/fullcalendar.print.css', // print
        'fullcalendar/scheduler.min.css'
    ];
    public $js = [
        'fullcalendar/lib/moment.min.js',
        'fullcalendar/lib/jquery-ui.custom.min.js',
        'fullcalendar/fullcalendar.min.js',
        'fullcalendar/lang-all.js',
        'fullcalendar/scheduler.min.js',
        'fullcalendar2.js'
    ];

    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }

}
