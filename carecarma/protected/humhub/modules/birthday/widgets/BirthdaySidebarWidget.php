<?php

namespace humhub\modules\birthday\widgets;

use Yii;
use humhub\modules\user\models\User;
use humhub\models\Setting;
use yii\log\Logger;

/**
 * BirthdaySidebarWidget displays the users of upcoming birthdays.
 *
 * It is attached to the dashboard sidebar.
 *
 * @package humhub.modules.birthday.widgets
 * @author Sebastian Stumpf
 */
class BirthdaySidebarWidget extends \yii\base\Widget
{

    public function run()
    {
        $range = (int) Setting::Get('shownDays', 'birthday');

        // Check if the next birthday is between the current date and (currentdate + range days)
        $birthdayCondition = "DATE_ADD(profile.birthday, 
                INTERVAL YEAR(CURDATE())-YEAR(profile.birthday)
                         + IF((CURDATE() > DATE_ADD(`profile`.birthday, INTERVAL (YEAR(CURDATE())-YEAR(profile.birthday)) YEAR)),1,0) YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL " . $range . " DAY)";

        $users = User::find()
                ->joinWith('profile')
                ->where($birthdayCondition)
                ->active()
                ->limit(10)
                ->all();

        $results = array();
        foreach ($users as $user){
            $groupId = $user->getUserGroup();
            if ($groupId == User::USERGROUP_PEOPLE){
               array_unshift($results, $user);
            } elseif ($groupId == User::USERGROUP_CIRCLEMEMBER) {
                array_push($results, $user);
            } elseif ($groupId == User::USERGROUP_SELF){
                $self = $user;
            }
        }
        if (isset($self)){
            array_unshift($results, $self);
        }


        
        // Sort birthday list
        usort($results, function($a, $b) {
            return $this->getDays($a) - $this->getDays($b);
        });

        if (count($results) == 0) {
            return;
        }

        return $this->render('birthdayPanel', array(
                    'users' => $results,
                    'dayRange' => $range
        ));
    }

    public function getDays($user)
    {
        $now = new \DateTime('now');
        $now->setTime(00, 00, 00);
        $nextBirthday = new \DateTime(date('y') . '-' . Yii::$app->formatter->asDate($user->profile->birthday, 'php:m-d'));

        $days = $nextBirthday->diff($now)->days;

        // Handle turn of year
        if ($days < 0) {
            $nextBirthday->modify('+1 year');
            $days = $nextBirthday->diff($now)->days;
        }

        return $days;
    }

    public function getAge($user)
    {
        $birthday = new \DateTime($user->profile->birthday);
        $age = $birthday->diff(new \DateTime('now'))->y;

        if ($this->getDays($user) != 0) {
            $age++;
        }

        return $age;
    }

}

?>
