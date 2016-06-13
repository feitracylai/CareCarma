<?php

namespace humhub\modules\user\models;

use Yii;
use humhub\libs\GCM;
use humhub\libs\Push;

/**
 * This is the model class for table "contact".
 *
 * @property integer $contact_id
 * @property string $contact_first
 * @property string $contact_last
 * @property string $contact_mobile
 * @property string $contact_email
 * @property string $nickname
 * @property integer $user_id
 * @property integer $contact_user_id
 * @property string $relation
 * @property string $device_phone
 * @property string $home_phone
 * @property string $work_phone
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_first', 'contact_last', 'contact_mobile'], 'required'],
            [['contact_mobile','device_phone','home_phone','work_phone'], 'number'],
            [['contact_email'], 'email'],
            [['user_id', 'contact_user_id'], 'integer'],
            [['contact_first', 'contact_last', 'contact_mobile', 'nickname', 'relation','device_phone','home_phone','work_phone'], 'string', 'max' => 255],
            [['contact_email'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_first' => Yii::t('UserModule.models_Contact', 'First Name'),
            'contact_last' => Yii::t('UserModule.models_Contact', 'Last Name'),
            'contact_mobile' => Yii::t('UserModule.models_Contact', 'Mobile#'),
            'device_phone' => Yii::t('UserModule.models_Contact', 'Device Phone#'),
            'home_phone' => Yii::t('UserModule.models_Contact', 'Home#'),
            'work_phone' => Yii::t('UserModule.models_Contact', 'Work#'),
            'contact_email' => Yii::t('UserModule.models_Contact', 'Email'),
            'nickname' => Yii::t('UserModule.models_Contact', 'Nickname'),
            'user_id' => Yii::t('UserModule.models_Contact', 'User ID'),
            'relation' => Yii::t('UserModule.models_Contact', 'Relation'),
            'contact_user_id' => Yii::t('UserModule.models_Contact', 'contact ID'),
        ];
    }

    public function notifyDevice($data) {
        $user = User::findOne(['id' => $this->user_id]);
        if ($user->device_id != null){
            $device = Device::findOne(['device_id' => $user->device_id]);
            if ($device->gcmId != null){
                $gcm = new GCM();
                $push = new Push();

                $push->setTitle('contact');
                $push->setData($data);

                $gcm_registration_id = $device->gcmId;

                $gcm->send($gcm_registration_id, $push->getPush());

            }
        }

    }



}
