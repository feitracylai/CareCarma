<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/12/2016
 * Time: 5:21 PM
 */

namespace humhub\modules\user\notifications;


use humhub\modules\notification\components\BaseNotification;
use yii\helpers\Url;

class AddContact extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = "user";

    /**
     * @inheritdoc
     */
    public $viewName = "addContact";


    public function getUrl()
    {
        return Url::to('index.php?r=user%2Fcontact%2Findex');
    }
}