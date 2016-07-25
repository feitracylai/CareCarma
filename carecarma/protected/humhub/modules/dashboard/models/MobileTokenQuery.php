<?php

namespace humhub\modules\dashboard\models;

/**
 * This is the ActiveQuery class for [[MobileToken]].
 *
 * @see MobileToken
 */
class MobileTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MobileToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MobileToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
