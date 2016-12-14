<?php

namespace humhub\modules\mail\models;

use humhub\modules\user\models;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Device;
use Yii;
use humhub\libs\GCM;
use yii\base\Model;
use yii\log\Logger;


/**
 * This is the model class for table "device_message".
 *
 * @property integer $type
 * @property integer $message_id
 * @property integer $user_id
 * @property integer $from_id
 * @property string $content
 */
//make some change to the DeviceMessage: make it a independent class, extends from Model, not extends from ActiveRecord
//class DeviceMessage extends \yii\db\ActiveRecord
class DeviceMessage extends Model
{
    public $type;
    public $message_id;
    public $user_id;
    public $from_id;
    public $content;

//    /**
//     * @inheritdoc
//     */
//    public static function tableName()
//    {
//        return 'device_message';
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['message_id', 'user_id', 'from_id', 'content'], 'required'],
//            [['message_id', 'user_id', 'from_id'], 'integer'],
//            [['content'], 'string'],
//            [['updated_at'], 'safe'],
//            [['isRead'], 'string', 'max' => 255],
//        ];
//    }

//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels()
//    {
//        return [
//            'message_id' => 'Message ID',
//            'user_id' => 'User ID',
//            'from_id' => 'From ID',
//            'content' => 'Content',
//            'isRead' => 'Is Read',
//        ];
//    }

    public function getData()
    {
        return [
            'type' => $this->type,
            'message'=> $this->message_id,
            'user_id'=> $this->user_id,
            'from_id'=> $this->from_id,
            'content'=> $this->content
        ];
    }

    public function notify()
    {
        //get the user info(device_id)
//        $user = User::findOne(['id' => $this->user_id]);
        //get the device info(gcm_id)
//        $device = Device::findOne(['device_id' => $user->device_id]);

        $gcm = new GCM();
//        $push = new Push();
//
//        $push->setTitle('message');
//        $push->setData($this->id);
//        Yii::getLogger()->log(print_r($this->user_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        $user = User::findOne(['id' => $this->user_id]);
//        Yii::getLogger()->log(print_r($user->device_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $device_list = Device::findAll(['user_id' => $this->user_id]);
//        $gcm_id = $device->gcmId;
//        Yii::getLogger()->log(print_r($this->getData(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        $gcm_registration_id = "eeajUUBkwG0:APA91bGZIyJ0XEO29JnDFhaJWFGLRw8mvJ4foQFfL_vcnQuEqXzaokLZdTeitpi8nvdlpurCIbcryd4AzM1x_FQVgAYbVvNpHOO0wTD4XuYi3OiMOlkVnk8-xcM9lbCbLFQ7qq1GALSs";
        Yii::getLogger()->log(print_r($this->getData(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        
//        $gcm->send($gcm_id, $this->getData());
        foreach($device_list as $device) {
            Yii::getLogger()->log($device, Logger::LEVEL_INFO, 'MyLog');
            if ($device != null) {
                Yii::getLogger()->log($device->device_id, Logger::LEVEL_INFO, 'MyLog');
                $gcm_id = $device->gcmId;
                $gcm->send($gcm_id, $this->getData());
            }
        }

    }
}
