<?php

namespace humhub\modules\devices\models;

use Yii;

/**
 * This is the model class for table "device_timezone".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $hardware_id
 * @property string $timezone
 * @property string $updated_time
 */
class DeviceTimezone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_timezone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'updated_time'], 'integer'],
            [['hardware_id', 'timezone'], 'string', 'max' => 255],
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
            'timezone' => 'Timezone',
            'updated_time' => 'Updated Time',
        ];
    }
}
