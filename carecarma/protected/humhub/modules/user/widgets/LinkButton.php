<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/7/2016
 * Time: 10:01 AM
 */

namespace humhub\modules\user\widgets;

use \yii\base\Widget;
use Yii;
use humhub\modules\user\models\User;
use humhub\modules\user\models\Contact;
use yii\log\Logger;

class LinkButton extends Widget
{
    public $user;

    public function run()
    {
        if ($this->user->isCurrentUser() || \Yii::$app->user->isGuest) {
            return;
        }

        $contact = Contact::findOne(['user_id' => $this->user->id, 'contact_user_id' => Yii::$app->user->id]);

        if ($contact == null || $contact->linked == 1) {
            return;
        }

        return $this->render('linkButton', [
            'contact' => $contact,
            'user' => $this->user
        ]);
    }
}