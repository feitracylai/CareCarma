<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/8/2016
 * Time: 6:21 PM
 */

namespace humhub\modules\user\notifications;



use humhub\modules\notification\components\BaseNotification;
use yii\helpers\Url;

class LinkDeclined extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $moduleId = "user";

    /**
     * @inheritdoc
     */
    public $viewName = "linkDeclined";

    public function getUrl()
    {
        return Url::to('index.php?r=user%2Fcontact%2Findex');
    }

}

?>