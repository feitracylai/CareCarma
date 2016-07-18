<?php

namespace humhub\modules\calendar\controllers;

use DateTime;
use humhub\modules\space\behaviors\SpaceModelMembership;
use humhub\modules\space\models\Membership;
use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\calendar\models\CalendarEntry;
use humhub\modules\user\models\Profile;

/**
 * ViewController displays the calendar on spaces or user profiles.
 *
 * @package humhub.modules_core.calendar.controllers
 * @author luke
 */
class ViewController extends ContentContainerController
{

    public $hideSidebar = true;

    public function actionIndex()
    {
//        Yii::getLogger()->log(print_r($this->contentContainer,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        return $this->render('index', array(
                    'contentContainer' => $this->contentContainer
        ));
    }

    public function actionLoadAjax()
    {
        Yii::$app->response->format = 'json';

        $output = array();

        $startDate = new DateTime(Yii::$app->request->get('start'));
        $endDate = new DateTime(Yii::$app->request->get('end'));

        $entries = CalendarEntry::getContainerEntriesByOpenRange($startDate, $endDate, $this->contentContainer);
//        Yii::getLogger()->log(print_r($entries,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        foreach ($entries as $entry) {
//            Yii::getLogger()->log(print_r($entry,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            $output[] = $entry->getFullCalendarArray();
        }
//        Yii::getLogger()->log(print_r($output,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $member_list = array();
        foreach (Membership::findAll(['space_id' => $this->contentContainer->primaryKey]) as $member) {
            $member_list[] = $member->user_id;
        }
        $new_output = array();
        foreach ($output as $element) {
            if (in_array($element['resourceId'], $member_list, 'TRUE')) {
                $new_output[] = $element;
            }
        }
        return $new_output;
    }

    public function actionResource() {
//        Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        Yii::$app->response->format = 'json';
        $output = array();
        $resource = array();
        $color_list = array("read", "green", "orange", "black", "blue", "violet");

        foreach (Membership::findAll(['space_id' => $this->contentContainer->primaryKey]) as $member) {
//            Yii::getLogger()->log(print_r($member,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            $resource = array();
            $resource['id'] = $member->user_id;
            $profile = Profile::findOne(['user_id' => $member->user_id]);
            $resource['title'] = $profile->firstname . ' ' . $profile->lastname;
            $resource['eventColor'] = $color_list[$member->user_id%6];
            $output[] = $resource;
        }
//        Yii::getLogger()->log(print_r($output,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $resource['id'] = 0;
        $resource['title'] = 'Others';
        $resource['eventColor'] = $color_list[$member->user_id%6];
        $output[] = $resource;
        return $output;
    }

}
