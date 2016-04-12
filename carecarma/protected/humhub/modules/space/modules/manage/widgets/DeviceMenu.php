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
            'label' => Yii::t('SpaceModule.widgets_SpaceMembersMenu', 'List Care Receiver'),
            'url' => $this->space->createUrl('/space/manage/device/index'),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id == 'index' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceMembersMenu', 'Add Account'),
            'url' => $this->space->createUrl('/space/manage/device/add'),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'add' && Yii::$app->controller->id === 'device'),
        ));

        parent::init();
    }

}