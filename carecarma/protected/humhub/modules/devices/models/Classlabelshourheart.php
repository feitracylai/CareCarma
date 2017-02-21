<?php

namespace humhub\modules\devices\models;

use Yii;

/**
 * This is the model class for table "ClassLabelsHourHeart".
 *
 * @property integer $id
 * @property string $time
 * @property integer $heartrateLabel
 * @property string $hardware_id
 */
class Classlabelshourheart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ClassLabelsHourHeart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['heartrateLabel'], 'integer'],
            [['time'], 'string', 'max' => 255],
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
            'time' => 'Time',
            'heartrateLabel' => 'Heartrate Label',
            'hardware_id' => 'Hardware ID',
        ];
    }
}
