<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\space\notifications;

use humhub\modules\user\models\User;
use yii\helpers\Html;
use humhub\modules\notification\components\BaseNotification;

/**
 * If an user was invited to a workspace, this notification is fired.
 *
 * @since 0.5
 */
class Invite extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = "space";

    /**
     * @inheritdoc
     */
    public $viewName = "invite";

    /**
     * @inheritdoc
     */
    public $markAsSeenOnClick = false;

    /**
     * @inheritdoc
     */
    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' invited you to the circle ' . Html::encode($this->source->name);
        return parent::send($user, $msg);
    }

}

?>
