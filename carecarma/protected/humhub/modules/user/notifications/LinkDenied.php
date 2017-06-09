<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/8/2016
 * Time: 6:21 PM
 */

namespace humhub\modules\user\notifications;



use humhub\modules\notification\components\BaseNotification;
use humhub\modules\user\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

class LinkDenied extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = "user";

    /**
     * @inheritdoc
     */
    public $viewName = "linkDenied";

    public function getUrl()
    {
        return Url::to('index.php?r=user%2Fcontact%2Findex');
    }

    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' denied your People request.';
        return parent::send($user, $msg);
    }

}

?>