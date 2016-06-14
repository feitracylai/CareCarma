<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "beacon".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $beacon_id
 * @property string $distance
 * @property string $datetime
 */
class Beacon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'beacon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['datetime'], 'safe'],
            [['distance'], 'string', 'max' => 100],
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
            'beacon_id' => 'Beacon ID',
            'distance' => 'Distance',
            'datetime' => 'Datetime',
        ];
    }
}
