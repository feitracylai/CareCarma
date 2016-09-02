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
 * SpaceApprovalRequestDeclinedNotification
 *
 * @since 0.5
 */
class ApprovalRequestDeclined extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = "space";

    /**
     * @inheritdoc
     */
    public $viewName = "approvalRequestDeclined";

    /**
     * @inheritdoc
     */
    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' declined your membership request for the circle ' . Html::encode($this->source->name);
        return parent::send($user, $msg);
    }
}

?>
