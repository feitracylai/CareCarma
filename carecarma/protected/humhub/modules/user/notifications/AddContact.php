<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/12/2016
 * Time: 5:21 PM
 */

namespace humhub\modules\user\notifications;


use humhub\modules\notification\components\BaseNotification;
use humhub\modules\user\models\User;
use yii\helpers\Url;
use yii\helpers\Html;

class AddContact extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = "user";

    /**
     * @inheritdoc
     */
    public $viewName = "addContact";


    public function getUrl()
    {
        return Url::to('index.php?r=user%2Fcontact%2Findex');
    }

    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' add you to People list.';
        return parent::send($user, $msg);
    }
}