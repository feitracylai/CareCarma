<?php

namespace humhub\modules\reminder\models;

use humhub\libs\GCM;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\Device;
use humhub\modules\user\models\User;
use Yii;
use yii\log\Logger;

/**
 * This is the model class for table "reminder_device".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $user_id
 * @property integer $update_user_id
 */
class ReminderDevice extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reminder_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['title', 'date', 'time', 'user_id', 'update_user_id'], 'required'],
            [['title', 'user_id', 'update_user_id'], 'required'],
            [['title', 'description'], 'string'],
            [['user_id', 'update_user_id'], 'integer'],
        ];
    }

    /**
     * @return string
     */


    /**
     * @param array $relatedRecords
     */


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }



    public function getTimes()
    {
        return $this->hasMany(ReminderDeviceTime::className(), ['reminder_id' => 'id']);
    }

    public function getSend()
    {
        return $this->hasOne(User::className(), ['id' => 'update_user_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'user_id' => 'User ID',
            'sent' => 'Sent',
            'update_user_id' => 'Update User ID',

        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (array_key_exists('title', $changedAttributes)){
            foreach (ReminderDeviceTime::findAll(['reminder_id' => $this->id]) as $deviceTime){
                $deviceTime->sendUpdateToDevice();
            }
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function delete()
    {
        foreach (ReminderDeviceTime::findAll(['reminder_id' => $this->id]) as $deviceTime){
            $deviceTime->delete();
        }

        return parent::delete(); // TODO: Change the autogenerated stub
    }
}
