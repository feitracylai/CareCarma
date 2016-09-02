<?php

namespace humhub\modules\calendar\controllers;

use DateTime;
use humhub\modules\space\behaviors\SpaceModelMembership;
use humhub\modules\space\models\Membership;
use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\calendar\models\CalendarEntry;
use humhub\modules\user\models\Profile;
use humhub\modules\calendar\models\CalendarEntryParticipant;

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
        $new_output = array();

        $entries_family = CalendarEntry::getContainerEntriesByOpenRange_family($startDate, $endDate, $this->contentContainer);
        foreach ($entries_family as $entry) {
            $temp = $entry->getFullCalendarArray();
            $temp['resourceId'] = 0;
            $new_output[] = $temp;
        }

        $member_list = array();
        foreach (Membership::findAll(['space_id' => $this->contentContainer->primaryKey]) as $member) {
            $member_list[] = $member->user_id;
        }

        foreach ($output as $element) {
            $calendarEntryParticipant = CalendarEntryParticipant::findAll(['calendar_entry_id' => $element['id']]);
//            Yii::getLogger()->log(print_r($calendarEntryParticipant,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            if (in_array($element['resourceId'], $member_list, 'TRUE')) {
                $temp = CalendarEntryParticipant::findOne(['calendar_entry_id' => $element['id'], 'user_id' => $element['resourceId']]);
                if ($temp->participation_state == 3) {
                    $new_output[] = $element;
                }
            }
            foreach ($calendarEntryParticipant as $ele) {
                if ($ele->user_id != $element['resourceId']) {
                    $new = array();
                    $new['id'] = $element['id'];
                    $new['resourceId'] = $ele->user_id;
                    $new['title'] = $element['title'];
                    $new['editable'] = $element['editable'];
                    $new['allDay'] = $element['allDay'];
                    $new['updateUrl'] = $element['updateUrl'];
                    $new['viewUrl'] = $element['viewUrl'];
                    $new['start'] = $element['start'];
                    $new['end'] = $element['end'];
                    if (in_array($new['resourceId'], $member_list, 'TRUE')) {
                        $temp = CalendarEntryParticipant::findOne(['calendar_entry_id' => $new['id'], 'user_id' => $new['resourceId']]);
                        if ($temp->participation_state == 3) {
                            $new_output[] = $new;
                        }
                    }
                }
            }
        }
//        Yii::getLogger()->log(print_r($new_output,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        return $new_output;
    }

    public function actionResource() {
//        Yii::getLogger()->log(print_r("AAA",true),yii\log\Logger::LEVEL_INFO,'MyLog');
        Yii::$app->response->format = 'json';
        $output = array();
        $resource = array();
        $color_list = array("green", "orange", "black", "blue", "violet");

        $resource['id'] = 0;
        $resource['title'] = 'Circle Events';
        $resource['eventColor'] = "red";
        $output[] = $resource;

        foreach (Membership::findAll(['space_id' => $this->contentContainer->primaryKey]) as $member) {
//            Yii::getLogger()->log(print_r($member,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            $resource = array();
            $resource['id'] = $member->user_id;
            $profile = Profile::findOne(['user_id' => $member->user_id]);
            $resource['title'] = $profile->firstname . ' ' . $profile->lastname;
            $resource['eventColor'] = $color_list[$member->user_id%5];
            $output[] = $resource;
        }
//        Yii::getLogger()->log(print_r($output,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $resource['id'] = 99999;
        $resource['title'] = 'Others';
        $resource['eventColor'] = $color_list[$member->user_id%5];
        $output[] = $resource;
        return $output;
    }

}
