<?php

namespace humhub\modules\user\models;

use Yii;
use yii\log\Logger;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property string $device_id
 * @property string $user_id
 * @property string $gcmId
 * @property string $phone
 * @property string $temp_password
 * @property string $hardware_id
 * @property string $type
 * @property string $model
 * @property integer $activate
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
            [['device_id'], 'unique'],
            [['device_id'], 'string', 'max' => 45],
            [['hardware_id'], 'string', 'max' => 15],
            [['gcmId', 'phone', 'type', 'model'], 'string', 'max' => 255],
            [['user_id', 'activate'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'device_id' => 'CoSMoS Activation#',
            'gcmId' => 'Gcm ID',
            'phone' => 'CoSMoS Phone#',
            'temp_password' => 'Temp Password',
            'user_id' => 'user ID',
            'hardware_id' => 'Hardware ID',
            'type' => 'Device Type',
            'model' => 'Device Model',
            'activate' => 'Is it activated?'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(\humhub\modules\user\models\User::className(), ['id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        //the device is in use, generate the device_show rows
        if (!$insert){
            if ($this->activate == 1){
                $user_id = $this->user_id;
                $receverMember = \humhub\modules\space\models\Membership::findOne(['user_id' => $user_id, 'group_id' => 'device']);
                if ($receverMember == null){
                    $exist = \humhub\modules\devices\models\DeviceShow::findAll(['report_user_id' => $user_id, 'hardware_id' => $this->hardware_id, 'user_id' => $user_id]);
                    if ($exist == null){
                        $device_show = new \humhub\modules\devices\models\DeviceShow();
                        $device_show->report_user_id = $user_id;
                        $device_show->hardware_id = $this->hardware_id;
                        $device_show->user_id = $user_id;
                        $device_show->save();
                    }

                }else {
                    $space_id = $receverMember->space_id;
                    $members = \humhub\modules\space\models\Membership::findAll(['space_id' => $space_id]);
                    foreach ($members as $member){
                        $exist = \humhub\modules\devices\models\DeviceShow::findAll(['report_user_id' => $user_id, 'hardware_id' => $this->hardware_id, 'user_id' => $member->user_id]);
                        if ($exist == null){
                            $device_show = new \humhub\modules\devices\models\DeviceShow();
                            $device_show->space_id = $space_id;
                            $device_show->report_user_id = $user_id;
                            $device_show->hardware_id = $this->hardware_id;
                            $device_show->user_id = $member->user_id;
                            $device_show->save();
                        }

                    }
                }
            }

            if ($this->activate == 0){
                $user_id = $this->user_id;
                $exists = \humhub\modules\devices\models\DeviceShow::findAll(['report_user_id' => $user_id, 'hardware_id' => $this->hardware_id]);
                if (count($exists) != 0){
                    foreach ($exists as $exist){
                        $exist->delete();
                    }
                }

            }
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }


}
