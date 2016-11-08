<?php
/**
 * Created by PhpStorm.
 * User: simonxu14
 * Date: 6/14/2016
 * Time: 4:10 PM
 */
namespace humhub\modules\user\models;



use yii\base\Model;
use humhub\libs\GCM;
use Yii;

class ContactInfo
{
    public $contact_id;
    public $contact_first;
    public $contact_last;
    public $contact_mobile;
    public $contact_email;
    public $nickname;
    public $user_id;
    public $contact_user_id;
    public $relation;
    public $home_phone;
    public $work_phone;
    public $photo;

//    public function getData()
//    {
//        return [
//            'user_id' => $this->user_id,
//            'contact_user_id'=> $this->contact_user_id,
//            'nickname'=> $this->nickname,
//            'contact_first'=> $this->contact_first,
//            'contact_last'=> $this->contact_last,
//            'contact_mobile' => $this->contact_mobile,
//            'relation' => $this->relation
//        ];
//    }

    

    public static function notify($contact_list)
    {

        $gcm = new GCM();
        $user = User::findOne(['id' => $contact_list['data'][0]['user_id']]);
        $device = Device::findOne(['id' => $user->device_id]);
        if ($device != null) {
            $gcm_id = $device->gcmId;
            $gcm->send($gcm_id, $contact_list);
        }


    }
}
