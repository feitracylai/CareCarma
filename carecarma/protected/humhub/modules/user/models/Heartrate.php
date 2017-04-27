<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "heartrate".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $hardware_id
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
            [['hardware_id'], 'required'],
            [['hardware_id'], 'string', 'max' => 15],
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
            'hardware_id' => 'IMEI #',
            'heartrate' => 'Heartrate',
            'datetime' => 'Datetime',
            'time' => 'Time'
        ];
    }
}
