<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/6/2016
 * Time: 4:56 PM
 */

namespace humhub\modules\user\notifications;

use humhub\modules\notification\components\BaseNotification;
use humhub\modules\user\models\User;
use yii\bootstrap\Html;
use yii\log\Logger;

class Linked extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = 'user';

    /**
     * @inheritdoc
     */
    public $viewName = "linked";

    /**
     * @inheritdoc
     */
    public $markAsSeenOnClick = false;

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->originator->getUrl();
    }

    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' wants to invite you to his/her People list.';
        return parent::send($user, $msg);
    }

}

?>