<?php


namespace humhub\modules\user\widgets;

use Yii;
use \humhub\widgets\BaseMenu;
use yii\helpers\Url;
use \humhub\modules\user\models\User;

/**
 * User Contact Menu
 *
 * @author Tracy
 */
class ContactMenu extends BaseMenu
{

    public $template = "@humhub/widgets/views/tabMenu";
    public $type = "adminUserSubNavigation";

    public function init()
    {

        $this->addItem(array(
            'label' => Yii::t('UserModule.views_account_editContact', 'Overview'),
            'url' => Url::toRoute(['/user/contact/index']),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id == 'index'),
        ));
        $this->addItem(array(
            'label' => Yii::t('UserModule.views_account_editContact', 'Add new contact'),
            'url' => Url::toRoute(['/user/contact/add']),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'add'),
        ));

        parent::init();
    }

}
