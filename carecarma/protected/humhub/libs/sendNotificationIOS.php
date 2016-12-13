<?PHP
namespace humhub\libs;
use Yii;


/**
 * @param $mobile_token
 * @param $msg
 * @return mixed
 */
class sendNotificationIOS
{
    function sendMessage($mobile_token,$msg )
    {
        $content = array(
            //"en" => $_POST["bodyNotif"]
            "en" => $msg,
        );

        $headings = array("en" => "CareCarma");

        $fields = array(
            'app_id' => "f457991a-3c2c-483f-bb70-08c65831f8b6",
            'include_player_ids' => array($mobile_token),
            'contents' => $content,
            'headings' => $headings
        );

        $fields = json_encode($fields);
       // print("\nJSON sent:\n");
       // print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic YTk4ZTVjNjAtZTRhMS00ZmJlLWJlZWYtYzUyZjhkMTZjOTMx'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }



}

?>