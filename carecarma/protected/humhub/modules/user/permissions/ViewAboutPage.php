<?php

namespace humhub\modules\user\permissions;

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
//        Yii::getLogger()->log($user->id, Logger::LEVEL_INFO, 'MyLog');
        if ($groupId == User::USERGROUP_FRIEND && $user->profile->privacy == 1 ) {
            return self::STATE_ALLOW;
        } elseif ($groupId == User::USERGROUP_USER && $user->profile->privacy == 2 ) {
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