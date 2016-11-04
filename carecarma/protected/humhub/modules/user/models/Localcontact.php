<?php

namespace humhub\modules\user\models;

use Yii;

/**
 * This is the model class for table "localcontact".
 *
 * @property integer $contact_id
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property string $phone_number1
 * @property string $phone_number2
 * @property string $phone_number3
 * @property string $token
 */
class Localcontact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'localcontact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone_number1' => 'Phone Number 1',
            'phone_number2' => 'Phone Number 2',
            'phone_number3' => 'Phone Number 3',
            'token' => 'Token'
        ];
    }
}
//=======
//<?php
//
//namespace humhub\modules\user\models;
//
//use Yii;
//
///**
// * This is the model class for table "localcontact".
// *
// * @property integer $contact_id
// * @property integer $user_id
// * @property string $name
// * @property string $email
// * @property string $phone_number1
// * @property string $phone_number2
// * @property string $phone_number3
// */
//class Localcontact extends \yii\db\ActiveRecord
//{
//    /**
//     * @inheritdoc
//     */
//    public static function tableName()
//    {
//        return 'localcontact';
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function rules()
//    {
//        return [
//            [['user_id'], 'required'],
//            [['user_id'], 'integer'],
//        ];
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'ID',
//            'user_id' => 'User ID',
//            'name' => 'Name',
//            'email' => 'Email',
//            'phone_number1' => 'Phone Number 1',
//            'phone_number2' => 'Phone Number 2',
//            'phone_number3' => 'Phone Number 3'
//        ];
//    }
//}
//>>>>>>> e126a80340ad67efde03781f30c50b54a1e23b17
