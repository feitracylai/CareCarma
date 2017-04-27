<?php
/**
 * Created by wufei
 */

namespace humhub\modules\space\modules\manage\widgets;

use Yii;
use yii\helpers\Url;
use \humhub\modules\space\models\Space;
use \humhub\modules\space\behaviors\SpaceController;


class ContactMenu extends \humhub\widgets\BaseMenu
{
    public $template = "@humhub/widgets/views/tabMenu";

    public $space;


    public function init()
    {
        $rguid =  Yii::$app->request->get('rguid');


        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Overview'),
            'url' => $this->space->createUrl('contact/index',['rguid' => $rguid]),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id == 'index'&& Yii::$app->controller->id === 'contact'),
        ));
        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Add contacts'),
            'url' => $this->space->createUrl('contact/add',['rguid' => $rguid]),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'add' && Yii::$app->controller->id === 'contact'),
        ));


        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu','Link console'),
            'url' => $this->space->createUrl('contact/console',['rguid' => $rguid]),
            'sortOrder' => 500,
            'isActive' => (Yii::$app->controller->action->id == 'console' && Yii::$app->controller->id === 'contact'),
        ));


        parent::init();
    }

}
