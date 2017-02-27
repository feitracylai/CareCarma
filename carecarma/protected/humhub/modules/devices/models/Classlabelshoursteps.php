<?php

namespace humhub\modules\devices\models;

use Yii;

/**
 * This is the model class for table "ClassLabelsHourSteps".
 *
 * @property integer $id
 * @property string $time
 * @property integer $stepsLabel
 * @property integer $nullData
 * @property string $hardware_id
 * @property string $updated_at
 * @property integer $seen
 */
class Classlabelshoursteps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ClassLabelsHourSteps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stepsLabel', 'nullData'], 'integer'],
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
            'stepsLabel' => 'Steps Label',
            'nullData' => 'Null Data',
            'hardware_id' => 'Hardware ID',
        ];
    }



}
