<?php
/**
 * Created by wufei
 */

namespace humhub\modules\space\modules\manage\widgets;

use Yii;
use yii\helpers\Url;


class CareEditMenu extends \humhub\widgets\BaseMenu
{
    public $template = "@humhub/widgets/views/tabMenu";

    public $space;

    public function init()

    {
        $id =  Yii::$app->request->get('id');

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Back'),
            'url' => $this->space->createUrl('/space/manage/device/index'),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id == 'index' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Edit Account'),
            'url' => $this->space->createUrl('/space/manage/device/edit',['id' => $id]),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'edit' && Yii::$app->controller->id === 'device'),
        ));


        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Profile'),
            'url' => $this->space->createUrl('/space/manage/device/profile',['id' => $id]),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->action->id == 'profile' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Device Setting'),
            'url' => $this->space->createUrl('/space/manage/device/device',['id' => $id]),
            'sortOrder' => 400,
            'isActive' => (Yii::$app->controller->action->id == 'device' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Contacts'),
            'url' => $this->space->createUrl('/space/manage/contact',['id' => $id]),
            'sortOrder' => 500,
            'isActive' => (Yii::$app->controller->id === 'contact'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Settings'),
            'url' => $this->space->createUrl('/space/manage/device/settings',['id' => $id]),
            'sortOrder' => 600,
            'isActive' => (Yii::$app->controller->action->id == 'settings' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Delete account'),
            'url' => $this->space->createUrl('/space/manage/device/delete',['id' => $id]),
            'sortOrder' => 1000,
            'isActive' => (Yii::$app->controller->action->id == 'delete' && Yii::$app->controller->id === 'device'),
        ));

        parent::init();
    }


}