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
        $rguid =  Yii::$app->request->get('rguid');

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', ' Back'),
            'url' => $this->space->createUrl('/space/manage/device/index'),
            'sortOrder' => 100,
            'icon' => '<i class="fa fa-backward"></i>',
            'isActive' => (Yii::$app->controller->action->id == 'index' && Yii::$app->controller->id === 'device'),

        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Edit Account'),
            'url' => $this->space->createUrl('/space/manage/device/edit',['rguid' => $rguid]),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'edit' && Yii::$app->controller->id === 'device'),
        ));


        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Profile'),
            'url' => $this->space->createUrl('/space/manage/device/profile',['rguid' => $rguid]),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->action->id == 'profile' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Cosmos Setting'),
            'url' => $this->space->createUrl('/space/manage/device/device',['rguid' => $rguid]),
            'sortOrder' => 400,
            'isActive' => (Yii::$app->controller->action->id == 'device' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Contacts'),
            'url' => $this->space->createUrl('/space/manage/contact',['rguid' => $rguid]),
            'sortOrder' => 500,
            'isActive' => (Yii::$app->controller->id === 'contact'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Settings'),
            'url' => $this->space->createUrl('/space/manage/device/settings',['rguid' => $rguid]),
            'sortOrder' => 600,
            'isActive' => (Yii::$app->controller->action->id == 'settings' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Delete account'),
            'url' => $this->space->createUrl('/space/manage/device/delete',['rguid' => $rguid]),
            'sortOrder' => 1000,
            'isActive' => (Yii::$app->controller->action->id == 'delete' && Yii::$app->controller->id === 'device'),
        ));

        parent::init();
    }


}