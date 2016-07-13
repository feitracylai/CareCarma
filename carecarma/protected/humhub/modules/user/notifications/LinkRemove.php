<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/13/2016
 * Time: 11:39 AM
 */

namespace humhub\modules\user\notifications;

use humhub\modules\notification\components\BaseNotification;
use yii\helpers\Url;

class LinkRemove extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = "user";

    /**
     * @inheritdoc
     */
    public $viewName = "linkRemove";

    public function getUrl()
    {
        return Url::to('index.php?r=user%2Fcontact%2Fconsole');
    }
}