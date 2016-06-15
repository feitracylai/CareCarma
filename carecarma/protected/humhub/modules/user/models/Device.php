<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property string $device_id
 * @property string $gcmId
 * @property string $phone
 * @property string $temp_password
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
            [['gcmId', 'phone'], 'string', 'max' => 255],
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
            'phone' => 'Device Phone#',
            'temp_password' => 'Temp Password'
        ];
    }




}
