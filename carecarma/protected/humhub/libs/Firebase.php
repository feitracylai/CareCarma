<?php

namespace humhub\libs;
use Yii;
use yii\log\Logger;


class Firebase
{

    public function send($to, $message)
    {
        
		$msg = array
        (
            'title' => 'CareCarma',
            'body' => $message,
            'sound' => 'mySound',
            'icon' => 'ic_status_icon',
            'iconColor' => '#42eef4',
        );
        $fields = array
        (
            'to' => $to,
             'priority' => "high",
            'notification' => $msg,
        );
        return $this->sendPushNotification($fields);
    }

    public function sendPushNotification($fields) {
		//Yii::getLogger()->log('sendFcm', Logger::LEVEL_INFO, 'MyLog');
        $api_key = 'AIzaSyBCpL8QgHY-sydrQepDLqma6jnsc_KyopQ';
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . $api_key,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
//	Yii::getLogger()->log($result, Logger::LEVEL_INFO, 'MyLog');
        curl_close($ch);

        return $result;
    }
}
