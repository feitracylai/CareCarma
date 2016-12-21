<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "sensor".
 *
 * @property integer $sensor_id
 * @property string $user_id
 * @property string $device_id
 * @property string $datetime
 * @property string $accelX
 * @property string $accelY
 * @property string $accelZ
 * @property string $GyroX
 * @property string $GyroY
 * @property string $GyroZ
 * @property string $CompX
 * @property string $CompY
 * @property string $CompZ
 * @property string $time
 */
class Sensor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sensor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['accelX', 'accelY', 'accelZ', 'GyroX', 'GyroY', 'GyroZ', 'CompX', 'CompY', 'CompZ', 'time'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sensor_id' => 'Sensor ID',
            'user_id' => 'User ID',
            'datetime' => 'Datetime',
            'accelX' => 'Accel X',
            'accelY' => 'Accel Y',
            'accelZ' => 'Accel Z',
            'GyroX' => 'Gyro X',
            'GyroY' => 'Gyro Y',
            'GyroZ' => 'Gyro Z',
            'CompX' => 'Comp X',
            'CompY' => 'Comp Y',
            'CompZ' => 'Comp Z',
            'time' => 'Time',
        ];
    }
}
