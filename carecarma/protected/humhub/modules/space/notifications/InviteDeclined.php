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
 * SpaceInviteDeclinedNotification is sent to the originator of the invite to
 * inform him about the decline.
 *
 * @since 0.5
 * @author Luke
 */
class InviteDeclined extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = "space";

    /**
     * @inheritdoc
     */
    public $viewName = "inviteDeclined";

    /**
     * @inheritdoc
     */
    public function send(User $user)
    {
        $msg =  Html::encode($this->source->displayName). ' declined your invite for the circle ' . Html::encode($this->source->name);
        return parent::send($user, $msg);
    }
}

?>
