<?php

namespace humhub\modules\space\widgets;

use Yii;
use \yii\base\Widget;

/**
 * The Main Navigation for a space. It includes the Modules the Stream
 *
 * @author Luke
 * @package humhub.modules_core.space.widgets
 * @since 0.5
 */
class BrowseMenu extends MenuWidget
{

    public $template = "application.widgets.views.leftNavigation";

    public function init()
    {

        $this->addItemGroup(array(
            'id' => 'browse',
            'label' => Yii::t('SpaceModule.widgets_SpaceBrowseMenuWidget', 'Circles'),
            'sortOrder' => 100,
        ));


        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceBrowseMenuWidget', 'My Circle List'),
            'url' => Yii::app()->createUrl('//space/browse', array()),
            'sortOrder' => 100,
            'isActive' => (Yii::app()->controller->id == "spacebrowse" && Yii::app()->controller->action->id == "index"),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceBrowseMenuWidget', 'My circle summary'),
            'url' => Yii::app()->createUrl('//dashboard', array()),
            'sortOrder' => 100,
            'isActive' => (Yii::app()->controller->id == "spacebrowse" && Yii::app()->controller->action->id == "index"),
        ));


        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceBrowseMenuWidget', 'Circle directory'),
            'url' => Yii::app()->createUrl('//community/workspaces', array()),
            'sortOrder' => 200,
            'isActive' => (Yii::app()->controller->id == "spacebrowse" && Yii::app()->controller->action->id == "index"),
        ));


#        $this->addItem(array(
#            'label' => Yii::t('SpaceModule.widgets_SpaceBrowseMenuWidget', 'Members'),
#            'url' => Yii::app()->createUrl('//space/space/members', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 200,
#            'isActive' => (Yii::app()->controller->id == "space" && Yii::app()->controller->action->id == "members"),
#        ));
#        $this->addItem(array(
#            'label' => Yii::t('SpaceModule.widgets_SpaceBrowseMenuWidget', 'Admin'),
#            'url' => Yii::app()->createUrl('//space/admin', array('sguid'=>$spaceGuid)),
#            'sortOrder' => 9999,
#            'isActive' => (Yii::app()->controller->id == "admin" && Yii::app()->controller->action->id == "index"),
#        ));


        parent::init();
    }

}

?>
