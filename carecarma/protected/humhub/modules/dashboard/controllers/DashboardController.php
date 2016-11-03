<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\dashboard\controllers;

use humhub\modules\dashboard\models\MobileToken;
use humhub\modules\dashboard\models\MobileTokenQuery;
use humhub\modules\tour\widgets\Dashboard;
use Yii;
use yii\web\Controller;
use humhub\models\Setting;

class DashboardController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
                'guestAllowedActions' => ['index', 'stream']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'stream' => [
                'class' => \humhub\modules\dashboard\components\actions\DashboardStream::className(),
            ],
        ];
    }

    /**
     * Dashboard Index
     *
     * Show recent wall entries for this user
     */
    public function actionIndex()
    {

        if (isset($_GET['yourKey'])) {
            $record= MobileToken::find()->where(['device_token'=>$_GET['yourKey']])->exists();

            $userId = Yii::$app->user->getId();
            if($record == null && $_GET['yourKey'] != "null") {
                $post = new  \humhub\modules\dashboard\models\MobileToken();
                $post->device_token = $_GET['yourKey'];
                $post->user_id = Yii::$app->user->getId();
                $post->save();
            }
            else if($record != null) {            // if user does sign out & login in with different account
                $post = MobileToken::find()->where(['device_token'=>$_GET['yourKey']])->one();
                $post->user_id = Yii::$app->user->getId();
                $post->save();
            }
        }

        if (Yii::$app->user->isGuest) {
            return $this->render('index_guest', array());
        } else {
            return $this->render('index', array('showProfilePostForm' => Setting::Get('showProfilePostForm', 'dashboard')));
        }
    }

    /*
    * Update user settings for hiding share panel on dashboard
    */
    public function actionHidePanel()
    {
        // set tour status to seen for current user
        return Yii::$app->user->getIdentity()->setSetting('hideSharePanel', 1, "share");
    }


}
