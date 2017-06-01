<?php

namespace humhub\modules\user\models;

use Yii;
use humhub\libs\GCM;
use humhub\libs\Push;
use yii\log\Logger;

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
 * @property integer $linked
 * @property string $relation
 * @property string $device_phone
 * @property string $home_phone
 * @property string $work_phone
 * @property integer $watch_primary_number
 * @property integer $phone_primary_number
 * @property integer $carecarma_watch_number
 * @property integer $glass_primary_number
 */
class Contact extends \yii\db\ActiveRecord
{
    private $oldAttrs = array();
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
            [['contact_first', 'contact_last'], 'required', 'on' => 'editContact'],
            [['user_id', 'contact_user_id', 'linked', 'glass_primary_number'], 'integer'],
            [['contact_first', 'contact_last', 'contact_mobile', 'nickname', 'relation','device_phone','home_phone','work_phone'], 'string', 'max' => 255],
            [['contact_email'], 'string', 'max' => 100],
            [['watch_primary_number'], \humhub\modules\user\components\CheckPrimaryWatch::className()],
            [['phone_primary_number'], \humhub\modules\user\components\CheckPrimaryPhone::className()],
            [['carecarma_watch_number'], \humhub\modules\user\components\CheckCareCarmaWatch::className()],
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
            'phone_primary_number' => Yii::t('UserModule.models_Contact', 'Primary Number on CoSMoS phone app'),
            'watch_primary_number' => Yii::t('UserModule.models_Contact', 'Primary Number on CoSMoS watch app'),
            'carecarma_watch_number' => Yii::t('UserModule.models_Contact', 'Primary Number on CareCarma Watch'),
            'glass_primary_number' => Yii::t('UserModule.models_Contact', 'Primary Number on CoSMoS Vue'),
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['editContact'] = ['contact_first', 'contact_last', 'contact_mobile', 'home_phone', 'work_phone', 'contact_email', 'nickname', 'relation', 'watch_primary_number', 'phone_primary_number', 'carecarma_watch_number, glass_primary_number'];
        $scenarios['linkContact'] = ['user_id', 'contact_user_id', 'linked'];
        return $scenarios;
    }


    public function getUser()
    {
        return $this->hasOne(\humhub\modules\user\models\User::className(), ['id' => 'contact_user_id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {

            if ($this->contact_mobile == null) {
                $this->contact_mobile = '';
            }
            if ($this->device_phone == null) {
                $this->device_phone = '';
            }
            if ($this->home_phone == null) {
                $this->home_phone = '';
            }
            if ($this->work_phone == null) {
                $this->work_phone = '';
            }
        }
//        Yii::getLogger()->log([$this->carecarma_watch_number, $this->glass_primary_number], Logger::LEVEL_INFO, 'MyLog');

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        // store history
        if (!$this->isNewRecord) {
            $newAttrs = $this->getAttributes();
            $oldAttrs = $this->getOldAttributes();

            if ($newAttrs['linked'] == 1){
                $devices = Device::find()->where(['user_id' => $this->user_id, 'activate' => 1])->all();
                $devices_array = array('Phone' => [], 'watch' => [], 'CWatch' => [], 'Glass' => [], 'null' => []);
                $device_list = array();
                if ($devices != null){
                    foreach ($devices as $device){
                        //separate gcmId in diff type
                        if ($device->type == 'Phone'){
                            $devices_array['Phone'][] = $device->gcmId;
                        } elseif ($device->type == 'watch'){
                            $devices_array['watch'][] = $device->gcmId;
                        }elseif ($device->type == 'CWatch'){
                            $devices_array['CWatch'][] = $device->gcmId;
                        } elseif ($device->type == 'Glass'){
                            $devices_array['Glass'][] = $device->gcmId;
                        } else {
                            $devices_array['null'][] = $device->gcmId;
                        }
                    }

                    //check if other attribute change
                    if ($oldAttrs['contact_first'] != $newAttrs['contact_first'] ||
                        $oldAttrs['contact_last'] != $newAttrs['contact_last'] ||
                        $oldAttrs['contact_mobile'] != $newAttrs['contact_mobile'] ||
                        $oldAttrs['home_phone'] != $newAttrs['home_phone'] ||
                        $oldAttrs['work_phone'] != $newAttrs['work_phone'] ||
                        $oldAttrs['contact_email'] != $newAttrs['contact_email'] ||
                        $oldAttrs['nickname'] != $newAttrs['nickname'] ||
                        $oldAttrs['relation'] != $newAttrs['relation'])
                    {
                        $device_list = array_merge($devices_array['Phone'], $devices_array['watch'], $devices_array['CWatch'], $devices_array['Glass'], $devices_array['null']);
                    } elseif ($oldAttrs == $newAttrs){
                        Yii::getLogger()->log('no change', Logger::LEVEL_INFO, 'MyLog');
                    } else {
                        $device_list = $devices_array['null'];
                        if ($oldAttrs['phone_primary_number'] != $newAttrs['phone_primary_number']){
                            $device_list = array_merge($device_list, $devices_array['Phone']);
                        }
                        if ($oldAttrs['watch_primary_number'] != $newAttrs['watch_primary_number']){
                            $device_list = array_merge($device_list, $devices_array['watch']);
                        }
                        if ($oldAttrs['carecarma_watch_number'] != $newAttrs['carecarma_watch_number']){
                            $device_list = array_merge($device_list, $devices_array['CWatch']);
                        }
                        if ($oldAttrs['glass_primary_number'] != $newAttrs['glass_primary_number']){
                            $device_list = array_merge($device_list, $devices_array['Glass']);
                        }
                    }


                }



//            Yii::getLogger()->log($device_list, Logger::LEVEL_INFO, 'MyLog');

                //begin to send GCM
                $data = array();
                $data['type'] = 'contact,updated';
                if (!empty($device_list)) {
                    foreach($device_list as $gcm_id) {
                        $gcm = new GCM();
                        $gcm->send($gcm_id, $data);
                    }
                }

                $image = "";

                if ($this->contact_user_id) {
                    $contact_user_temp = User::findOne(['id' => $this->contact_user_id]);
                    $profileImage = new \humhub\libs\ProfileImage($contact_user_temp->guid);
                    $pos = strpos($profileImage->getUrl(), "?m=");
                    $image = substr($profileImage->getUrl(), 0, $pos);
                }

                $data2 = array();
                $data2['type'] = 'contact,edit';
                $data2['contact_id'] = $this->contact_id;
                $data2['contact_user_id'] = $this->contact_user_id;
                $data2['photo'] = $image;
                $data2['contact_first'] = $this->contact_first;
                $data2['contact_last'] = $this->contact_last;
                $data2['nickname'] = $this->nickname;
                $data2['relation'] = $this->relation;
                $data2['contact_mobile'] = $this->contact_mobile;
                $data2['device_phone'] = $this->device_phone;
                $data2['home_phone'] = $this->home_phone;
                $data2['work_phone'] = $this->work_phone;
                $data2['contact_email'] = $this->contact_email;
                $data2['watch_primary_number'] = $this->watch_primary_number;
                $data2['phone_primary_number'] = $this->phone_primary_number;
                $data2['carecarma_watch_number'] = $this->carecarma_watch_number;
                $data2['glass_primary_number'] = $this->glass_primary_number;


                if (!empty($device_list)) {
                    foreach($device_list as $gcm_id) {
                        $gcm = new GCM();
                        $gcm->send($gcm_id, $data2);
                    }
                }
            } else {

            }


        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
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

    public function getAddPush() {
        return [
            'type' => 'contact,add',
            'contact_id' => $this->contact_id,
            'contact_first' => $this->contact_first,
            'contact_last' => $this->contact_last,
            'contact_mobile' => $this->contact_mobile,
            'contact_email' => $this->contact_email,
            'nickname' => $this->nickname,
            'user_id' => $this->user_id,
            'contact_user_id' => $this->contact_user_id,
            'relation' => $this->relation,
            'home_phone' => $this->home_phone,
            'work_phone' => $this->work_phone,
            'watch_primary_number' => $this->watch_primary_number,
            'phone_primary_number' => $this->phone_primary_number,
            'carecarma_watch_number' => $this->carecarma_watch_number,
            'glass_primary_number' => $this->glass_primary_number,
        ];
    }

    public function getDeletePush() {
        return [
            'type' => 'contact,delete',
            'user_id' => $this->user_id,
            'contact_id' => $this->contact_id,
            'contact_user_id' => $this->contact_user_id
        ];
    }

    public function getUpdatePush() {
        return [
            'type' => 'contact,update',
            'contact_id' => $this->contact_id,
            'contact_first' => $this->contact_first,
            'contact_last' => $this->contact_last,
            'contact_mobile' => $this->contact_mobile,
            'contact_email' => $this->contact_email,
            'nickname' => $this->nickname,
            'user_id' => $this->user_id,
            'contact_user_id' => $this->contact_user_id,
            'relation' => $this->relation,
            'home_phone' => $this->home_phone,
            'work_phone' => $this->work_phone,
            'watch_primary_number' => $this->watch_primary_number,
            'phone_primary_number' => $this->phone_primary_number,
            'carecarma_watch_number' => $this->carecarma_watch_number,
            'glass_primary_number' => $this->glass_primary_number,
        ];
    }


//    public function notifyDevice($data) {
//
//
//
//        if ($data == 'add') {
//            $user = User::findOne(['id' => $this->user_id]);
//            if ($user->device_id != null){
//                $device = Device::findOne(['device_id' => $user->device_id]);
//                if ($device->gcmId != null){
//                    $gcm = new GCM();
//                    $gcm_registration_id = $device->gcmId;
//                    $gcm->send($gcm_registration_id, $this->getAddPush());
//
//                }
//            }
//
//
//        }
//
//        if ($data == 'delete') {
//            $user = User::findOne(['id' => $this->user_id]);
//            if ($user->device_id != null){
//                $device = Device::findOne(['device_id' => $user->device_id]);
//                if ($device->gcmId != null){
//                    $gcm = new GCM();
//                    $gcm_registration_id = $device->gcmId;
//                    $gcm->send($gcm_registration_id, $this->getDeletePush());
//
//                }
//            }
//        }
//
//
//        if ($data == 'update') {
//            $user = User::findOne(['id' => $this->user_id]);
//            if ($user->device_id != null){
//                $device = Device::findOne(['device_id' => $user->device_id]);
//                if ($device->gcmId != null){
//                    $gcm = new GCM();
//                    $gcm_registration_id = $device->gcmId;
//                    $gcm->send($gcm_registration_id, $this->getUpdatePush());
//
//                }
//            }
//        }
//
//
//
////        $user = User::findOne(['id' => $this->user_id]);
////        if ($user->device_id != null){
////            $device = Device::findOne(['device_id' => $user->device_id]);
////            if ($device->gcmId != null){
////                $gcm = new GCM();
//////                $push = new Push();
////
////                $push->setTitle('contact');
////                $push->setData($data);
////
////                $gcm_registration_id = $device->gcmId;
////
////                $gcm->send($gcm_registration_id, $push->getPush());
////
////            }
////        }
//    }

//    public static function notify($contact_list)
//    {
//
//        $gcm = new GCM();
//        $user = User::findOne(['id' => $contact_list['data'][0]['user_id']]);
//        $device = Device::findOne(['id' => $user->device_id]);
//        $gcm_id = $device->gcmId;
////        Yii::getLogger()->log(print_r($gcm_id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
//
////        Yii::getLogger()->log(print_r(json_encode($contact_list),true),yii\log\Logger::LEVEL_INFO,'MyLog');
//        $gcm->send($gcm_id, json_encode($contact_list));
//
//
//    }



}
