<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 1/12/2017
 * Time: 12:53 PM
 */

namespace humhub\modules\devices\widgets;

use humhub\components\Widget;
use yii\log\Logger;

class Notifications extends Widget
{

    /**
     * Creates the Wall Widget
     */
    public function run()
    {

        return $this->render('notifications', array(
        ));
    }
}