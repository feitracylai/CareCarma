<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 8/24/2016
 * Time: 11:43 AM
 */

namespace humhub\widgets;

use humhub\modules\user\models\User;
use Yii;

class BackgroundConfig extends \yii\base\Widget
{

    /**
     * Displays / Run the Widget
     */
    public function run()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);



        return $this->render('background', array(
            'user' => $user,
        ));
    }

}