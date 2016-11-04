<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 5/31/2016
 * Time: 2:43 PM
 */

namespace humhub\modules\user\device;

use Yii;
use yii\db\ActiveRecord;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Device;
use humhub\libs\GCM;
use humhub\libs\Push;

class activate
{
    public function init()
    {
        $device_id = Yii::$app->request->get('device_id');
        $gcm_id = Yii::$app->request->get('gcm_id');

        $device = Device::findOne(['device_id' => $device_id]);
        if ($device != null) {
            $device->gcmId = $gcm_id;
            $device->save();

            $user = User::findOne(['device_id' => $device_id]);
            if ($user != null) {
                $gcm = new GCM();
                $push = new Push();

                $push->setTitle('binding');
                $push->setData($user->getId());

                $gcm->send($gcm_id, $push->getPush());
            }
        } else {
            $gcm = new GCM();
            $push = new Push();

            $push->setTitle('binding');
            $push->setData('failed');

            $gcm->send($gcm_id, $push->getPush());
        }

        return $this->render('index');
    }

}



