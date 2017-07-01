<?php

namespace humhub\modules\devices\models;

use Yii;

/**
 * This is the model class for table "device_show".
 *
 * @property integer $id
 * @property integer $space_id
 * @property integer $report_user_id
 * @property string $hardware_id
 * @property integer $user_id
 * @property string $updated_at
 * @property integer $seen
 */
class DeviceShow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_show';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['space_id', 'report_user_id', 'user_id', 'seen'], 'integer'],
            [['updated_at'], 'safe'],
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
            'space_id' => 'Space ID',
            'report_user_id' => 'Report User ID',
            'hardware_id' => 'Hardware ID',
            'user_id' => 'User ID',
            'updated_at' => 'Updated At',
            'seen' => 'Seen',
        ];
    }
}
