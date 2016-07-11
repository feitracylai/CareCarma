<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/6/2016
 * Time: 4:56 PM
 */

namespace humhub\modules\user\notifications;

use humhub\modules\notification\components\BaseNotification;

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
    public function getUrl()
    {
        return $this->originator->getUrl();
    }

}

?>