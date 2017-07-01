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
 * SpaceApprovalRequestNotification
 *
 * @since 0.5
 */
class ApprovalRequest extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = "space";

    /**
     * @inheritdoc
     */
    public $viewName = "approvalRequest";

    /**
     * @inheritdoc
     */
    public $markAsSeenOnClick = false;

    /**
     * @inheritdoc
     */
    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' requests membership for the circle ' . Html::encode($this->source->name);
        return parent::send($user, $msg);
    }
}

?>
