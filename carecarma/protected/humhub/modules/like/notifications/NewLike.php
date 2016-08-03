<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\like\notifications;

use humhub\modules\user\models\User;
use yii\helpers\Html;
use humhub\modules\notification\components\BaseNotification;

/**
 * Notifies a user about likes of his objects (posts, comments, tasks & co)
 *
 * @since 0.5
 */
class NewLike extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = 'like';

    /**
     * @inheritdoc
     */
    public $viewName = "newLike";

    /**
     * @inheritdoc
     */
    public function send(User $user)
   {
        $msg =  Html::encode($this->originator->displayName). ' likes ' . parent::getContentInfo($this->source->getSource());
        return parent::send($user, $msg);
    }
}

?>
