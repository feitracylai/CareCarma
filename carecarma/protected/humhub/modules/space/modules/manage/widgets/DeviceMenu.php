<?php
/**
 * User: wufei
 * Date: 4/4/2016
 * Time: 4:14 PM
 */
namespace humhub\modules\space\modules\manage\widgets;

use Yii;
use yii\helpers\Url;

class DeviceMenu extends \humhub\widgets\BaseMenu
{
    public $template = "@humhub/widgets/views/tabMenu";

    public $space;

    public function init()
    {
        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_DeviceMenu', 'Overview'),
            'url' => $this->space->createUrl('/space/manage/device/index'),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id == 'index' && Yii::$app->controller->id === 'device'),
        ));

        if ($this->space->isAdmin()){
            $this->addItem(array(
                'label' => Yii::t('SpaceModule.widgets_DeviceMenu', 'Add Care'),
                'url' => $this->space->createUrl('/space/manage/device/add'),
                'sortOrder' => 200,
                'isActive' => (Yii::$app->controller->action->id == 'add' || Yii::$app->controller->action->id == 'add-care' && Yii::$app->controller->id === 'device'),
            ));
        }

        parent::init();
    }

}