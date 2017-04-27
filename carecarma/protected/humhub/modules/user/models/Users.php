<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $username
 * @property string $profilename
 * @property string $email
 * @property string $mobile
 * @property string $password
 * @property string $usertype
 * @property string $taskeravailability
 * @property string $montaskeravailability
 * @property string $tuetaskeravailability
 * @property string $wedtaskeravailability
 * @property string $thutaskeravailability
 * @property string $fritaskeravailability
 * @property string $sattaskeravailability
 * @property string $suntaskeravailability
 * @property string $sunstarttime
 * @property string $sunendtime
 * @property string $monstarttime
 * @property string $monendtime
 * @property string $tuestarttime
 * @property string $tueendtime
 * @property string $wedstarttime
 * @property string $wedendtime
 * @property string $thustarttime
 * @property string $thuendtime
 * @property string $fristarttime
 * @property string $friendtime
 * @property string $satstarttime
 * @property string $satendtime
 * @property string $questions
 * @property string $takeradditiontocommunity
 * @property string $takernotworking
 * @property string $takermakesure
 * @property string $havevechile
 * @property string $vechiletype
 * @property string $taskerthingstobring
 * @property string $taskeraddthings
 * @property string $quickpitch
 * @property string $experience
 * @property string $skills
 * @property string $workarea
 * @property string $status
 * @property string $background
 * @property string $insurance
 * @property string $logintype
 * @property string $mobileveificationcode
 * @property string $emailverificationcode
 * @property string $createdon
 * @property string $modifiedon
 * @property string $lastlogindate
 * @property string $lastloginip
 * @property string $image
 * @property string $biography
 * @property string $unitnumber
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $postalcode
 * @property string $paypalemail
 * @property string $accountverified
 * @property string $facebook
 * @property string $google
 * @property string $dob
 * @property string $gender
 * @property string $adminremarks
 * @property string $privilege
 * @property integer $twitterid
 * @property string $phonetype
 * @property string $lattidude
 * @property string $longitude
 * @property string $latlonname
 * @property integer $completesignup
 * @property integer $createdyear
 * @property string $activation_code
 * @property string $ipaddress
 * @property string $sandbox_stripe_access_token
 * @property string $sandbox_stripe_refresh_token
 * @property string $sandbox_stripe_publishable_key
 * @property string $sandbox_stripe_user_id
 * @property string $sandbox_stripe_token_type
 * @property string $live_stripe_access_token
 * @property string $live_stripe_refresh_token
 * @property string $live_stripe_publishable_key
 * @property string $live_stripe_user_id
 * @property string $live_stripe_token_type
 * @property string $stripe_customerid
 * @property string $polylat
 * @property string $polylon
 * @property string $polygoncoordinate
 * @property integer $taskerboundary
 * @property string $distanceby
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['firstname', 'lastname', 'username', 'profilename', 'email', 'mobile', 'password', 'usertype', 'taskeravailability', 'montaskeravailability', 'tuetaskeravailability', 'wedtaskeravailability', 'thutaskeravailability', 'fritaskeravailability', 'sattaskeravailability', 'suntaskeravailability', 'sunstarttime', 'sunendtime', 'monstarttime', 'monendtime', 'tuestarttime', 'tueendtime', 'wedstarttime', 'wedendtime', 'thustarttime', 'thuendtime', 'fristarttime', 'friendtime', 'satstarttime', 'satendtime', 'questions', 'takeradditiontocommunity', 'takernotworking', 'takermakesure', 'havevechile', 'vechiletype', 'taskerthingstobring', 'taskeraddthings', 'quickpitch', 'experience', 'skills', 'workarea', 'status', 'background', 'insurance', 'logintype', 'mobileveificationcode', 'emailverificationcode', 'createdon', 'modifiedon', 'lastlogindate', 'lastloginip', 'image', 'biography', 'unitnumber', 'address', 'city', 'state', 'country', 'paypalemail', 'accountverified', 'facebook', 'google', 'dob', 'gender', 'adminremarks', 'privilege', 'twitterid', 'phonetype', 'lattidude', 'longitude', 'latlonname', 'completesignup', 'createdyear', 'activation_code', 'ipaddress', 'sandbox_stripe_access_token', 'sandbox_stripe_refresh_token', 'sandbox_stripe_publishable_key', 'sandbox_stripe_user_id', 'sandbox_stripe_token_type', 'live_stripe_access_token', 'live_stripe_refresh_token', 'live_stripe_publishable_key', 'live_stripe_user_id', 'live_stripe_token_type', 'stripe_customerid', 'polylat', 'polylon', 'polygoncoordinate', 'taskerboundary', 'distanceby'], 'required'],
            [['questions', 'havevechile', 'skills', 'status', 'background', 'insurance', 'emailverificationcode', 'biography', 'accountverified', 'adminremarks', 'privilege', 'distanceby'], 'string'],
            [['createdon', 'modifiedon', 'lastlogindate', 'dob'], 'safe'],
            [['twitterid', 'completesignup', 'createdyear', 'taskerboundary'], 'integer'],
            [['firstname'], 'string', 'max' => 55],
            [['lastname', 'profilename', 'state'], 'string', 'max' => 60],
            [['username', 'usertype', 'taskeravailability', 'vechiletype', 'taskerthingstobring', 'taskeraddthings', 'phonetype', 'lattidude', 'longitude', 'latlonname', 'activation_code', 'ipaddress', 'polylat', 'polylon'], 'string', 'max' => 250],
            [['email', 'password', 'experience', 'workarea', 'image', 'address', 'paypalemail', 'facebook', 'google', 'sandbox_stripe_access_token', 'sandbox_stripe_refresh_token', 'sandbox_stripe_publishable_key', 'sandbox_stripe_user_id', 'sandbox_stripe_token_type', 'live_stripe_access_token', 'live_stripe_refresh_token', 'live_stripe_publishable_key', 'live_stripe_user_id', 'live_stripe_token_type', 'stripe_customerid'], 'string', 'max' => 255],
            [['mobile', 'mobileveificationcode'], 'string', 'max' => 45],
            [['montaskeravailability', 'tuetaskeravailability', 'wedtaskeravailability', 'thutaskeravailability', 'fritaskeravailability', 'sattaskeravailability', 'suntaskeravailability'], 'string', 'max' => 125],
            [['sunstarttime', 'sunendtime', 'monstarttime', 'monendtime', 'tuestarttime', 'tueendtime', 'wedstarttime', 'wedendtime', 'thustarttime', 'thuendtime', 'fristarttime', 'friendtime', 'satstarttime', 'satendtime', 'lastloginip', 'postalcode', 'gender'], 'string', 'max' => 50],
            [['takeradditiontocommunity', 'takernotworking', 'takermakesure', 'quickpitch', 'polygoncoordinate'], 'string', 'max' => 500],
            [['logintype', 'city', 'country'], 'string', 'max' => 65],
            [['unitnumber'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'username' => 'Username',
            'profilename' => 'Profilename',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'password' => 'Password',
            'usertype' => 'Usertype',
            'taskeravailability' => 'Taskeravailability',
            'montaskeravailability' => 'Montaskeravailability',
            'tuetaskeravailability' => 'Tuetaskeravailability',
            'wedtaskeravailability' => 'Wedtaskeravailability',
            'thutaskeravailability' => 'Thutaskeravailability',
            'fritaskeravailability' => 'Fritaskeravailability',
            'sattaskeravailability' => 'Sattaskeravailability',
            'suntaskeravailability' => 'Suntaskeravailability',
            'sunstarttime' => 'Sunstarttime',
            'sunendtime' => 'Sunendtime',
            'monstarttime' => 'Monstarttime',
            'monendtime' => 'Monendtime',
            'tuestarttime' => 'Tuestarttime',
            'tueendtime' => 'Tueendtime',
            'wedstarttime' => 'Wedstarttime',
            'wedendtime' => 'Wedendtime',
            'thustarttime' => 'Thustarttime',
            'thuendtime' => 'Thuendtime',
            'fristarttime' => 'Fristarttime',
            'friendtime' => 'Friendtime',
            'satstarttime' => 'Satstarttime',
            'satendtime' => 'Satendtime',
            'questions' => 'Questions',
            'takeradditiontocommunity' => 'Takeradditiontocommunity',
            'takernotworking' => 'Takernotworking',
            'takermakesure' => 'Takermakesure',
            'havevechile' => 'Havevechile',
            'vechiletype' => 'Vechiletype',
            'taskerthingstobring' => 'Taskerthingstobring',
            'taskeraddthings' => 'Taskeraddthings',
            'quickpitch' => 'Quickpitch',
            'experience' => 'Experience',
            'skills' => 'Skills',
            'workarea' => 'Workarea',
            'status' => 'Status',
            'background' => 'Background',
            'insurance' => 'Insurance',
            'logintype' => 'Logintype',
            'mobileveificationcode' => 'Mobileveificationcode',
            'emailverificationcode' => 'Emailverificationcode',
            'createdon' => 'Createdon',
            'modifiedon' => 'Modifiedon',
            'lastlogindate' => 'Lastlogindate',
            'lastloginip' => 'Lastloginip',
            'image' => 'Image',
            'biography' => 'Biography',
            'unitnumber' => 'Unitnumber',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'postalcode' => 'Postalcode',
            'paypalemail' => 'Paypalemail',
            'accountverified' => 'Accountverified',
            'facebook' => 'Facebook',
            'google' => 'Google',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'adminremarks' => 'Adminremarks',
            'privilege' => 'Privilege',
            'twitterid' => 'Twitterid',
            'phonetype' => 'Phonetype',
            'lattidude' => 'Lattidude',
            'longitude' => 'Longitude',
            'latlonname' => 'Latlonname',
            'completesignup' => 'Completesignup',
            'createdyear' => 'Createdyear',
            'activation_code' => 'Activation Code',
            'ipaddress' => 'Ipaddress',
            'sandbox_stripe_access_token' => 'Sandbox Stripe Access Token',
            'sandbox_stripe_refresh_token' => 'Sandbox Stripe Refresh Token',
            'sandbox_stripe_publishable_key' => 'Sandbox Stripe Publishable Key',
            'sandbox_stripe_user_id' => 'Sandbox Stripe User ID',
            'sandbox_stripe_token_type' => 'Sandbox Stripe Token Type',
            'live_stripe_access_token' => 'Live Stripe Access Token',
            'live_stripe_refresh_token' => 'Live Stripe Refresh Token',
            'live_stripe_publishable_key' => 'Live Stripe Publishable Key',
            'live_stripe_user_id' => 'Live Stripe User ID',
            'live_stripe_token_type' => 'Live Stripe Token Type',
            'stripe_customerid' => 'Stripe Customerid',
            'polylat' => 'Polylat',
            'polylon' => 'Polylon',
            'polygoncoordinate' => 'Polygoncoordinate',
            'taskerboundary' => 'Taskerboundary',
            'distanceby' => 'Distanceby',
        ];
    }
}
