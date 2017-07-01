<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/15/2016
 * Time: 12:05 PM
 */

namespace humhub\modules\admin\widgets;

use humhub\widgets\BaseMenu;
use Yii;
use yii\helpers\Url;

class DeviceMenu extends BaseMenu
{


    public $template = "@humhub/widgets/views/tabMenu";
    public $type = "adminUserSubNavigation";

    public function init()
    {
        $this->addItem(array(
            'label' => Yii::t('AdminModule.views_device_index', 'Overview'),
            'url' => Url::toRoute(['/admin/device/index']),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'admin' && Yii::$app->controller->id == 'device' && Yii::$app->controller->action->id == 'index'),
        ));
        $this->addItem(array(
            'label' => Yii::t('AdminModule.views_device_index', 'Add new device'),
            'url' => Url::toRoute(['/admin/device/add']),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'admin' && Yii::$app->controller->id == 'device' && Yii::$app->controller->action->id == 'add'),
        ));

        parent::init();
    }
}