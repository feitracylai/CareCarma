<?php
/**
 * Created by PhpStorm.
 * User: simonxu14
 * Date: 7/18/2016
 * Time: 1:29 PM
 */


namespace humhub\modules\calendar\widgets;

use Yii;
use yii\helpers\Url;
use humhub\models\Setting;


class CalendarMenu extends \humhub\widgets\BaseMenu
{
    public $template = "@humhub/widgets/views/tabMenu";

    public function init()
    {

        $this->addItem(array(
            'label' => Yii::t('AdminModule.views_user_index', 'Add new family event'),
            'url' => Url::toRoute(['/calendar/entry/edit']),
            'sortOrder' => 100,
            'isActive' => 'TRUE',
        ));

        parent::init();
    }
}