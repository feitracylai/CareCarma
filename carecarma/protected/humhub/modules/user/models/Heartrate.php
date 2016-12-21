<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "heartrate".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $device_id
 * @property string $heartrate
 * @property string $datetime
 * @property string $time
 */
class Heartrate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'heartrate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['device_id'], 'required'],
            [['device_id'], 'integer'],
            [['heartrate'], 'safe'],
            [['heartrate'], 'string', 'max' => 100],
            [['time'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'device_id' => 'Device ID',
            'heartrate' => 'Heartrate',
            'datetime' => 'Datetime',
            'time' => 'Time'
        ];
    }
}
