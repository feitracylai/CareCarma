<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/11/2017
 * Time: 3:47 PM
 */

namespace humhub\modules\reminder\widgets;


use humhub\components\Widget;
use Yii;

class ShowReminder extends Widget
{

    public function run()
    {

        return $this->render('showReminder', array());
    }
}