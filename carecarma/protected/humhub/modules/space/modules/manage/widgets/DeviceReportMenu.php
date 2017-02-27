<?php
/**
 * Created by wufei
 */

namespace humhub\modules\space\modules\manage\widgets;

use Yii;


class DeviceReportMenu extends \humhub\widgets\BaseMenu
{
    public $template = "@humhub/widgets/views/tabMenu";

    public $space;

    public function init()

    {
        $rguid =  Yii::$app->request->get('rguid');

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceMembersMenu', ' Back'),
            'url' => $this->space->createUrl('/space/manage/device/index'),
            'sortOrder' => 100,
            'icon' => '<i class="fa fa-backward"></i>',
            'isActive' => (Yii::$app->controller->action->id == 'index' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceMembersMenu', 'Steps'),
            'url' => $this->space->createUrl('/space/manage/device/report', ['rguid' => $rguid]),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'report' && Yii::$app->controller->id === 'device'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceMembersMenu', 'Heart Rate'),
            'url' => $this->space->createUrl('/space/manage/device/report-heartrate', ['rguid' => $rguid]),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->action->id == 'report-heartrate' && Yii::$app->controller->id === 'device'),
        ));


        parent::init();

    }

}

?>