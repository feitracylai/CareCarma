<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "sensor_step_counter".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $hardware_id
 * @property integer $steps
 * @property string $datetime
 * @property string $time
 */
class SensorStepCounter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sensor_step_counter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'steps', 'time'], 'integer'],
            [['datetime'], 'safe'],
            [['hardware_id'], 'string', 'max' => 15],
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
            'hardware_id' => 'Hardware ID',
            'steps' => 'Steps',
            'datetime' => 'Datetime',
            'time' => 'Time',
        ];
    }
}
