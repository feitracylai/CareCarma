<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/12/2016
 * Time: 12:23 PM
 */

namespace humhub\modules\user\models\forms;


use Yii;
use yii\base\Model;
use humhub\modules\user\models\User;

class SecuritySetting extends Model
{
    public $contact_notify_setting;
    public $view_about_page;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array(['contact_notify_setting'], 'in',
                'range' => array(
                    User::CONTACT_NOTIFY_EVERYONE,
                    User::CONTACT_NOTIFY_NOCIRCLE,
                    User::CONTACT_NOTIFY_NOONE,)
            ),
            array(['view_about_page'], 'in',
                'range' => array(
                    User::ABOUT_PAGE_PRIVATE,
                    User::ABOUT_PAGE_PUBLIC,
                )),

        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'contact_notify_setting' => Yii::t('UserModule.forms_SecuritySettingForm', 'Who needs your verification?'),
            'view_about_page' => Yii::t('UserModule.forms_SecuritySettingForm', 'Who can see your profile info?'),
        );
    }

}