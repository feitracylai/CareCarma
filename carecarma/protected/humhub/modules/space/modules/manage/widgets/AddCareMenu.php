<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/23/2016
 * Time: 10:40 AM
 */

namespace humhub\modules\space\modules\manage\widgets;

use Yii;
use yii\helpers\Url;

class AddCareMenu  extends \humhub\widgets\BaseMenu
{
    public $template = "@humhub/widgets/views/tabMenu";

    public $space;

    public function init()
    {



            $this->addItem(array(
                'label' => Yii::t('SpaceModule.widgets_AddCareMenu', 'Care Memebers'),
                'url' => $this->space->createUrl('/space/manage/device/add-care'),
                'sortOrder' => 100,
                'isActive' => (Yii::$app->controller->action->id == 'add-care' && Yii::$app->controller->id === 'device'),
            ));

        $this->addItem(array(
            'label' => Yii::t('SpaceModule.widgets_AddCareMenu', 'Add New Account'),
            'url' => $this->space->createUrl('/space/manage/device/add'),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'add' && Yii::$app->controller->id === 'device'),
        ));

        parent::init();
    }
}