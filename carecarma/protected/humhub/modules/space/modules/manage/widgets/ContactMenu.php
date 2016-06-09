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
        $id =  Yii::$app->request->get('id');


        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Overview'),
            'url' => $this->space->createUrl('contact/index',['id' => $id]),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id == 'index'&& Yii::$app->controller->id === 'contact'),
        ));
        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Add new contact'),
            'url' => $this->space->createUrl('contact/add',['id' => $id]),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'add' && Yii::$app->controller->id === 'contact'),
        ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_SpaceCareMenu', 'Import new contact'),
            'url' => $this->space->createUrl('contact/import',['id' => $id]),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->action->id == 'import' && Yii::$app->controller->id === 'contact'),
        ));


        parent::init();
    }

}
