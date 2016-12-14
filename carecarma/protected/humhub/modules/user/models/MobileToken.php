<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "mobile_token".
 *
 * @property integer $id
 * @property string $device_token
 * @property integer $user_id
 * @property string $created_at
 */
class MobileToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mobile_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_token' => 'Device Token',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }
}
