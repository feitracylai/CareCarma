<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/26/2016
 * Time: 1:47 PM
 */

namespace humhub\modules\space\notifications;

use yii\helpers\Html;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\user\models\User;


class AddCare extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = "space";

    /**
     * @inheritdoc
     */
    public $viewName = "addCare";


    /**
     * @inheritdoc
     */
    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' add you as an Care Receiver in circle ' . Html::encode($this->source->name) . '. If you accept it, all the administrators in' . Html::encode($this->source->name) . 'can manage your account.';
        return parent::send($user, $msg);
    }
}