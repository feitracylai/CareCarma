<?php

namespace humhub\modules\user\models;

use Yii;
use humhub\libs\GCM;
use humhub\libs\Push;

/**
 * This is the model class for table "contact".
 *
 * @property integer $contact_id
 * @property string $AndroidId
 * @property string $contact_first
 * @property string $contact_last
 * @property string $contact_mobile
 * @property string $contact_email
 * @property string $nickname
 * @property integer $user_id
 * @property string $isRead
 * @property integer $contact_user_id
 * @property string $relation
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
            [['contact_mobile'], 'number'],
            [['contact_email'], 'email'],
            [['user_id', 'contact_user_id'], 'integer'],
            [['contact_first', 'contact_last', 'contact_mobile', 'nickname', 'isRead', 'relation'], 'string', 'max' => 255],
            [['AndroidId'], 'string', 'max' => 100],
            [['contact_email'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'contact_id' => Yii::t('UserModule.models_Contact', 'ID'),
            'contact_first' => Yii::t('UserModule.models_Contact', 'First Name'),
            'contact_last' => Yii::t('UserModule.models_Contact', 'Last Name'),
            'contact_mobile' => Yii::t('UserModule.models_Contact', 'Mobile#'),
            'contact_email' => Yii::t('UserModule.models_Contact', 'Email'),
            'nickname' => Yii::t('UserModule.models_Contact', 'Nickname'),
            'user_id' => Yii::t('UserModule.models_Contact', 'User ID'),
            'relation' => Yii::t('UserModule.models_Contact', 'Relation'),
        ];
    }

    public function notifyDevice($user, $data) {
        if ($user->gcmId != null){
            $gcm = new GCM();
            $push = new Push();

            $push->setTitle('contact');
            $push->setData($data);

            $gcm_registration_id = $user->gcmId;

            $gcm->send($gcm_registration_id, $push->getPush());

        }
    }


}
