<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\massuserimport\models;

use humhub\modules\user\models\User;
/**
 * Extends user by massuserimport scenarios.
 */
class MassuserimportUser extends User
{
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['massuserimport_create'] = ['username', 'email', 'group_id', 'super_admin', 'auth_mode', 'status', 'tags', 'language', 'visibility', 'timezone'];
        $scenarios['massuserimport_update'] = ['username', 'email', 'group_id', 'super_admin', 'auth_mode', 'status', 'tags', 'language', 'visibility', 'timezone'];
        return $scenarios;
    }    
    
    /**
     * We want that relation filled up with a MassuserimportProfile model to have access to its scenarios.
     * @see \humhub\modules\user\models\User::getProfile()
     */
    public function getProfile()
    {
        return MassuserimportProfile::find()->where(['user_id' => $this->id]);
    }
}
