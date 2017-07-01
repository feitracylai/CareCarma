<?php

namespace humhub\modules\reminder\models;

use humhub\libs\GCM;
use humhub\modules\admin\models\Log;
use humhub\modules\user\models\Device;
use Yii;
use yii\log\Logger;

/**
 * This is the model class for table "reminder_device_time".
 *
 * @property integer $id
 * @property integer $reminder_id
 * @property string $time
 * @property integer $repeat
 * @property string $date
 * @property string $day
 * @property  string $deadline
 * @property integer $remove_sent
 */
class ReminderDeviceTime extends \yii\db\ActiveRecord
{
    private $oldAttrs = array();

    const REMIND_ONCE = 0;
    const REMIND_DAY = 1;
    const REMIND_EVERYDAY = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reminder_device_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time'], 'required'],
            [['reminder_id', 'repeat', 'remove_sent'], 'integer'],
            [['time', 'date', 'day', 'deadline'], 'string'],

            ['date', 'required', 'when' => function ($model) {
                return $model->repeat == 0;
            }],
            ['day', 'required', 'when' => function ($model) {
                return $model->repeat == 1;
            }],
        ];
    }

    public function beforeSave($insert)
    {
        $deadline_change = false;
        /**check deadline changed**/
        if ($insert) {
            $deadline_change = true;
        } else {
            $oldAttrs = $this->getOldAttributes();
            if ($this->deadline != $oldAttrs['deadline']) {
                $deadline_change = true;
            }
        }

        if ($deadline_change) {
            if ($this->deadline == '') {
                $this->remove_sent = null;
            } else {
                $this->remove_sent = 0;
            }
        }

        /***date & day remove from repeat***/
        if ($this->repeat == 0){
            $this->day = '';
            $this->deadline = '';
        } else {
            $this->date = '';
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


    public function afterSave($insert, $changedAttributes)
    {
//        Yii::getLogger()->log($this->id, Logger::LEVEL_INFO, 'MyLog');
        $send = false;
        /***add new reminder***/
        if ($insert) {
            $send = true;
            /***update***/
        } else {
            $newAttrs = $this->getAttributes();
            $oldAttrs = $this->getOldAttributes();

            if ($newAttrs != $oldAttrs) {
                if ($oldAttrs['time'] != $newAttrs['time'] || $oldAttrs['repeat'] != $newAttrs['repeat'] ||
                    $oldAttrs['date'] != $newAttrs['date'] || $oldAttrs['day'] != $newAttrs['day']){
                    $send = true;
                }
            }
        }

        if ($send) {
            $this->sendUpdateToDevice();
        }


        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reminder_id' => 'Reminder ID',
            'time' => 'Time',
            'repeat' => 'Repeat',
            'date' => 'Date *',
            'day' => 'Day *',
            'deadline' => 'Expiration Date'
        ];
    }

    /**
     * @return string
     */
    public function getReminder()
    {
        return $this->hasOne(ReminderDevice::className(), ['id' => 'reminder_id']);
    }

    public function afterFind()
    {
        // Save old values
        $this->setOldAttributes($this->getAttributes());

        return parent::afterFind();
    }

    public function getOldAttributes()
    {
        return $this->oldAttrs;
    }

    public function setOldAttributes($attrs)
    {
        $this->oldAttrs = $attrs;
    }

    /**
     * @return string
     */
    public function afterDelete()
    {
        $devices = Device::findAll(['user_id' => $this->reminder->user_id, 'activate' => 1]);
        if (count($devices) != 0) {
            $data = array(
                'type' => 'reminder,delete',
                'id' => $this->id
            );


            foreach ($devices as $device) {
                $gcm = new GCM();
                $gcm->send($device->gcmId, $data);
            }
        }
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public function sendUpdateToDevice()
    {
        $reminder = ReminderDevice::findOne(['id' => $this->reminder_id]);
        $devices = Device::findAll(['user_id' => $reminder->user_id, 'activate' => 1]);
        if (count($devices) != 0) {


            $data = array();
            $data['type'] = 'reminder,add';


            $data['id'] = $this->id;

            $data['title'] = $reminder->title;
            $data['description'] = $reminder->description;
            //$data['send'] = $reminder->send->firstname;

            $data['hour'] = date_format(date_create($this->time), "H");
            $data['minutes'] = date_format(date_create($this->time), "i");

            if ($this->repeat == 0) {

                $data['repeat'] = $this::REMIND_ONCE;
                $data['date'] = date_format(date_create($this->date), "d");
                $data['month'] = date_format(date_create($this->date), "m");
                $data['year'] = date_format(date_create($this->date), "Y");


            } elseif ($this->repeat == 1) {
                /******if it is everyday**********/
                if ($this->day == 0) {
                    $data['repeat'] = $this::REMIND_EVERYDAY;
                } else {
                    $data['repeat'] = $this::REMIND_DAY;
                    $data['day'] = $this->day;
                }

            }

            foreach ($devices as $device) {
                $gcm = new GCM();
                $gcm->send($device->gcmId, $data);
                //json_decode($result, true) is an array;
            }

        }
    }

}


