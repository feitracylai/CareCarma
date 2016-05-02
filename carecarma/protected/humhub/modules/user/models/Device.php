<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property string $device_id
 * @property string $gcmId
 * @property integer $user_id
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id'], 'string', 'max' => 45],
            [['gcmId'], 'string', 'max' => 255],
            [['user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'device_id' => 'Device ID',
            'gcmId' => 'Gcm ID',
        ];
    }




}
