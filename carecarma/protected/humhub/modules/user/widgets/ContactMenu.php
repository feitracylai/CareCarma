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

    public $template = "@humhub/widgets/views/leftNavigation";
    public $type = "contactNavigation";

    public function init()
    {

        $this->addItemGroup(array(
            'id' => 'people',
            'label' => Yii::t('UserModule.widgets_ContactMenuWidget', '<strong>People</strong> '),
            'sortOrder' => 100,
        ));

        $this->addItem(array(
            'label' => Yii::t('UserModule.views_account_editContact', 'Overview'),
            'url' => Url::toRoute(['/user/contact/index']),
            'group' => 'people',
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id == 'index'),
        ));

        $this->addItem(array(
            'label' => Yii::t('UserModule.views_account_editContact', 'Add people'),
            'url' => Url::toRoute(['/user/contact/add']),
            'group' => 'people',
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == 'add'),
        ));

//        $this->addItem(array(
//            'label' => Yii::t('UserModule.views_account_editContact', 'Create new contact'),
//            'url' => Url::toRoute(['/user/contact/create']),
//            'sortOrder' => 300,
//            'isActive' => (Yii::$app->controller->action->id == 'create'),
//        ));



//        $this->addItem(array(
//            'label' => Yii::t('UserModule.views_account_editContact','Link console'),
//            'url' => Url::toRoute(['/user/contact/console']),
//            'sortOrder' => 400,
//            'isActive' => (Yii::$app->controller->action->id == 'console'),
//        ));
//
//        $this->addItem(array(
//            'label' => Yii::t('UserModule.views_account_editContact','Privacy settings'),
//            'url' => Url::toRoute(['/user/contact/setting']),
//            'sortOrder' => 500,
//            'isActive' => (Yii::$app->controller->action->id == 'setting'),
//        ));

        parent::init();
    }

}
