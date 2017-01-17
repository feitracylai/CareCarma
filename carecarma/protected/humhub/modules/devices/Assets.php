<?php

/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/13/2017
 * Time: 3:19 PM
 */

namespace humhub\modules\devices;

use yii\web\AssetBundle;


class Assets extends AssetBundle
{


    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }
}