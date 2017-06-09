<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/13/2016
 * Time: 11:39 AM
 */

namespace humhub\modules\user\notifications;

use humhub\modules\notification\components\BaseNotification;
use humhub\modules\user\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

class LinkRemove extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = "user";

    /**
     * @inheritdoc
     */
    public $viewName = "linkRemove";

    public function getUrl()
    {
        return Url::to('index.php?r=user%2Fcontact%2Fconsole');
    }

    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' remove you in his/her People list.';
        return parent::send($user, $msg);
    }
}