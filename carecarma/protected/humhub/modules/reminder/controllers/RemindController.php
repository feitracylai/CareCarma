<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 4/18/2017
 * Time: 5:01 PM
 */

namespace humhub\modules\reminder\controllers;


use humhub\components\Controller;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\reminder\models\ReminderDeviceSearch;
use humhub\modules\space\models\Space;
use Yii;
use yii\log\Logger;

class RemindController extends ContentContainerController
{

//    public $hideSidebar = true;
    public function actionIndex()
    {

        return $this->renderAjax('index');
    }

}
