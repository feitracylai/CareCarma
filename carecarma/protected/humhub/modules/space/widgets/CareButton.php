<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/26/2016
 * Time: 4:21 PM
 */

namespace humhub\modules\space\widgets;

use Yii;
use \yii\base\Widget;
use yii\log\Logger;

class CareButton extends Widget
{

    public $space;

    public function run()
    {
        $membership = $this->space->getMembership();

        if (Yii::$app->user->isGuest || $membership == null)
        {
            return;
        }


        if ($membership->add_care != '0')
        {
            return;
        }

        return $this->render('careButton', array(
            'space' => $this->space,
            'membership' => $membership
        ));

    }

}