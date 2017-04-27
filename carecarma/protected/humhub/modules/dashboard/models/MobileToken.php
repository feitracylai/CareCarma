<?php

namespace humhub\modules\dashboard\models;

use Yii;

/**
 * This is the model class for table "mobile_token".
 *
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
            [['device_token', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['device_token'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'device_token' => 'Device Token',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     * @return MobileTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MobileTokenQuery(get_called_class());
    }
}
