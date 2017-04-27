<?php

namespace humhub\modules\user\permissions;

use humhub\modules\space\models\Membership;
use Yii;
use humhub\modules\user\models\User;
use yii\log\Logger;

class ViewAboutPage extends \humhub\libs\BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        User::USERGROUP_SELF,
        User::USERGROUP_PEOPLE,
        User::USERGROUP_CIRCLEMEMBER
    ];

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        User::USERGROUP_SELF
    ];

    /**
     * @inheritdoc
     */
    public function getDefaultState($groupId)
    {
        $user = User::findOne(['guid' => Yii::$app->request->get('uguid')]);
        $notify_permission = $user->getSetting("contact_notify_setting", 'contact', \humhub\models\Setting::Get('contact_notify_setting', 'send'));
//        Yii::getLogger()->log($notify_permission, Logger::LEVEL_INFO, 'MyLog');
        if ($groupId == User::USERGROUP_USER && $notify_permission == User::ABOUT_PAGE_PUBLIC){
            return self::STATE_ALLOW;
        }


        return parent::getDefaultState($groupId);
    }

    /**
     * @inheritdoc
     */
    protected $title = "About page";

    /**
     * @inheritdoc
     */
    protected $description = "Allows access to your about page";

    /**
     * @inheritdoc
     */
    protected $moduleId = 'user';
}