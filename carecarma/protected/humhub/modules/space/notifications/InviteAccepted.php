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
 * SpaceInviteAcceptedNotification is sent to the originator of the invite to
 * inform him about the accept.
 *
 * @since 0.5
 * @author Luke
 */
class InviteAccepted extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = "space";

    /**
     * @inheritdoc
     */
    public $viewName = "inviteAccepted";

    /**
     * @inheritdoc
     */
    public function send(User $user)
    {
        $msg =  Html::encode($this->originator->displayName). ' accepted your invite for the family ' . Html::encode($this->source->name);
        return parent::send($user, $msg);
    }
}

?>
