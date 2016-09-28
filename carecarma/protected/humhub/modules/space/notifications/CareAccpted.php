<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/26/2016
 * Time: 2:48 PM
 */

namespace humhub\modules\space\notifications;


use humhub\modules\user\models\User;
use yii\helpers\Html;
use humhub\modules\notification\components\BaseNotification;

class CareAccpted extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = "space";

    /**
     * @inheritdoc
     */
    public $viewName = "careAccepted";

    /**
     * @inheritdoc
     */
    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' accepted your Care Receiver added for the circle ' . Html::encode($this->source->name);
        return parent::send($user, $msg);
    }

    public function getUrl()
    {
        return $this->source->createUrl('/space/manage/device');
    }
}

?>