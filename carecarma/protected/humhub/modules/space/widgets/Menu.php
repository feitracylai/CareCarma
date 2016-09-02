<?php

namespace humhub\modules\space\widgets;

use Yii;
use \yii\base\Widget;
use yii\log\Logger;

/**
 * The Main Navigation for a space. It includes the Modules the Stream
 *
 * @author Luke
 * @package humhub.modules_core.space.widgets
 * @since 0.5
 */
class Menu extends \humhub\widgets\BaseMenu
{

    public $space;
    public $template = "@humhub/widgets/views/leftNavigation";

    public function init()
    {

        // Reckon the current controller is a valid space controller
        // (Needs to implement the SpaceControllerBehavior)
        $spaceGuid = Yii::$app->controller->getSpace()->guid;

        $this->addItemGroup(array(
            'id' => 'modules',
            'label' => Yii::t('SpaceModule.widgets_SpaceMenuWidget', '<strong>Circle</strong> menu'),
            'sortOrder' => 100,
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceMenuWidget', 'Stream'),
            'group' => 'modules',
            'url' => $this->space->createUrl(),
            'icon' => '<i class="fa fa-bars"></i>',
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->id == "space" && Yii::$app->controller->action->id == "index" && Yii::$app->controller->module->id == "space"),
        ));

#        $this->addItem(array(
#            'label' => Yii::t('SpaceModule.widgets_SpaceMenuWidget', 'Members'),
#            'url' => Yii::$app->createUrl('//space/space/members', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 200,
#            'isActive' => (Yii::$app->controller->id == "space" && Yii::$app->controller->action->id == "members"),
#        ));
#        $this->addItem(array(
#            'label' => Yii::t('SpaceModule.widgets_SpaceMenuWidget', 'Admin'),
#            'url' => Yii::$app->createUrl('//space/admin', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 9999,
#            'isActive' => (Yii::$app->controller->id == "admin" && Yii::$app->controller->action->id == "index"),
#        ));

        if ($this->space->isMember()) {
                $this->addItem(array(
                    'label' => Yii::t('SpaceModule.widgets_SpaceMenuWidget', 'Care receiver'),
                    'group' => 'modules',
                    'url' => $this->space->createUrl('/space/manage/device'),
                    'icon' => '<i class="fa fa-headphones"></i>',
                    'sortOrder' => 210,
                    'isActive' => (Yii::$app->controller->id == "device"),
                ));
            }


        parent::init();
    }

}

?>
