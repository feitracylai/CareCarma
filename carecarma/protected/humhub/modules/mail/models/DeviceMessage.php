<?php

namespace humhub\modules\mail\models;

use humhub\modules\user\models\User;
use Yii;
use humhub\libs\GCM;
use humhub\libs\Push;


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
class DeviceMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'user_id', 'from_id', 'content'], 'required'],
            [['message_id', 'user_id', 'from_id'], 'integer'],
            [['content'], 'string'],
            [['updated_at'], 'safe'],
            [['isRead'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'user_id' => 'User ID',
            'from_id' => 'From ID',
            'content' => 'Content',
            'updated_at' => 'Updated At',
            'isRead' => 'Is Read',
        ];
    }

    public function notify()
    {
        $user = User::findOne(['id' => $this->user_id]);

        $gcm = new GCM();
        $push = new Push();

        $push->setTitle('message');
        $push->setData($this->id);

        $gcm_registration_id = $user->gcmId;
        $gcm->send($gcm_registration_id, $push->getPush());
    }
}
