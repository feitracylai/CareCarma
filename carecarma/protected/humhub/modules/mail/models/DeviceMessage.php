<?php

namespace humhub\modules\mail\models;

use humhub\modules\user\models;
use humhub\modules\user\models\User;
use Yii;
use humhub\libs\GCM;
use yii\base\Model;


/**
 * This is the model class for table "device_message".
 *
 * @property integer $id
 * @property integer $message_id
 * @property integer $user_id
 * @property integer $from_id
 * @property string $content
 * @property string $updated_at
 * @property string $isRead
 */
//make some change to the DeviceMessage: make it a independent class, extends from Model, not extends from ActiveRecord
//class DeviceMessage extends \yii\db\ActiveRecord
class DeviceMessage extends Model
{
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

//        $gcm_registration_id = $device->gcmId;
        $gcm_registration_id = "f0xbn7sVFAc:APA91bHjgGeWPI__YvBCetVHawkhhiRwidrxQZQ1c5Jjg3a-MTl_xJTMFjSc6O35PtwK_9vsa60EtP83Ffupaj1tJnhjZ3Lp3vabkkb-GVtQqheGiRB4OrDAeaM3xW5AsQpNksh1wvhC";
//        Yii::getLogger()->log(print_r($this->getData(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        
        $gcm->send($gcm_registration_id, $this->getData());
    }
}
