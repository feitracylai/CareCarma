<?php

namespace humhub\modules\mail\controllers;
use humhub\modules\dashboard\models\MobileToken;
use humhub\modules\dashboard\models\MobileTokenQuery;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\Device;
use Yii;
use yii\helpers\Html;
use yii\log\Logger;
use yii\web\HttpException;
use humhub\libs\GCM;
use humhub\libs\Push;
use humhub\components\Controller;
use humhub\modules\file\models\File;
use humhub\modules\mail\models\Message;
use humhub\modules\mail\models\MessageEntry;
use humhub\modules\mail\models\UserMessage;
use humhub\modules\mail\models\DeviceMessage;
use humhub\modules\User\models\User;
use humhub\modules\mail\models\forms\InviteRecipient;
use humhub\modules\mail\models\forms\ReplyMessage;
use humhub\modules\mail\models\forms\CreateMessage;
use humhub\modules\mail\permissions\SendMail;
use humhub\modules\user\widgets\UserPicker;
use humhub\modules\space\models\Membership;
use humhub\libs\Firebase;
use humhub\libs\sendNotificationIOS;

/**
 * MailController provides messaging actions.
 *
 * @package humhub.modules.mail.controllers
 * @since 0.5
 */
class MailController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
            ]
        ];
    }

    /**
     * Overview of all messages
     */
    public function actionIndex()
    {
        // Initially displayed message
        $messageId = (int)Yii::$app->request->get('id');

        $query = UserMessage::find();
        $query->joinWith('message');
        $query->where(['user_message.user_id' => Yii::$app->user->id]);
        $query->orderBy('message.updated_at DESC');

        $countQuery = clone $query;
        $messageCount = $countQuery->count();
        $pagination = new \yii\data\Pagination(['totalCount' => $messageCount, 'pageSize' => 25]);
        $query->offset($pagination->offset)->limit($pagination->limit);

        $userMessages = $query->all();

        // If no messageId is given, use first if available
        if (($messageId == "" || $this->getMessage($messageId) === null) && $messageCount != 0) {
            $messageId = $userMessages[0]->message->id;
        }

        return $this->render('/mail/index', array(
            'userMessages' => $userMessages,
            'messageId' => $messageId,
            'pagination' => $pagination
        ));
    }

    /**
     * Overview of all messages
     * Used by MailNotificationWidget to display all recent messages
     */
    public function actionNotificationList()
    {
        $query = UserMessage::find();
        $query->joinWith('message');
        $query->where(['user_message.user_id' => Yii::$app->user->id]);
        $query->orderBy('message.updated_at DESC');
        $query->limit(5);

        return $this->renderAjax('notificationList', array('userMessages' => $query->all()));
    }

    /**
     * Shows a Message Thread
     */
    public function actionShow()
    {
        // Load Message
        $id = (int)Yii::$app->request->get('id');
        $message = $this->getMessage($id);

        $this->checkMessagePermissions($message);

        // Reply Form
        $replyForm = new ReplyMessage();
        if ($replyForm->load(Yii::$app->request->post()) && $replyForm->validate()) {
            // Attach Message Entry
            $messageEntry = new MessageEntry();
            $messageEntry->message_id = $message->id;
            $messageEntry->user_id = Yii::$app->user->id;
            $messageEntry->content = $replyForm->message;
            $messageEntry->save();
            $messageEntry->notify();

//            File::attachPrecreated($messageEntry, Yii::$app->request->post('fileUploaderHiddenGuidField'));
            $txt1 = print_r($messageEntry->content, true);
            $SenderUserid = print_r($messageEntry->user_id, true);
            $MessageID = print_r($messageEntry->message_id, true);
            $AllUserid = UserMessage::findAll(['message_id' => $messageEntry->message_id]);
            $Allreceipt = array();
            foreach ($AllUserid as $test) {
                if ($test->user_id != $SenderUserid) {
                    $Allreceipt[] = $test->user_id;
                    $users_tokenT = MobileToken::find()->where(['user_id' => $test->user_id])->all();
                    if ($users_tokenT != null) {
                        foreach ($users_tokenT as $userToken) {
                            $mobile_token = $userToken->device_token;
//                            $sendNot = new sendNotificationIOS(); //for IOS device
//                            $sendNot->sendMessage($mobile_token, $txt1);
                            $firebase = new Firebase(); //only for Android device
                            $firebase->send($mobile_token, $txt1);
                        }
                    }
                }
            }


            //device

            foreach (UserMessage::find()->where(['message_id' => $message->id])->each() as $userMessage) {
//                $user = User::findOne(['id' => $userMessage->user_id]);
//               Yii::getLogger()->log($userMessage->user_id, Logger::LEVEL_INFO, 'MyLog');
                if ($userMessage->user_id != Yii::$app->user->id) {
                   //Yii::getLogger()->log($userMessage->user_id, Logger::LEVEL_INFO, 'MyLog');
                    $deviceMessage = new DeviceMessage();
                    $deviceMessage->type = "message,reply";
                    $deviceMessage->message_id = $message->id;
                    $deviceMessage->user_id = $userMessage->user_id;
                    $deviceMessage->from_id = Yii::$app->user->id;
                    $deviceMessage->content = $messageEntry->content;
                    $deviceMessage->notify();


                }

            }

            return $this->htmlRedirect(['index', 'id' => $message->id]);
        }
        // Marks message as seen
        $message->seen(Yii::$app->user->id);

        return $this->renderAjax('/mail/show', [
            'message' => $message,
            'replyForm' => $replyForm,
        ]);
    }


    private function checkMessagePermissions($message)
    {
        if ($message == null) {
            throw new HttpException(404, 'Could not find message!');
        }

        if (!$message->isParticipant(Yii::$app->user->getIdentity())) {
            throw new HttpException(403, 'Access denied!');
        }
    }

    /**
     * Shows the invite user form
     *
     * This method invite new people to the conversation.
     */
    public function actionAddUser()
    {
        $id = Yii::$app->request->get('id');
        $message = $this->getMessage($id);

        $this->checkMessagePermissions($message);

        // Invite Form
        $inviteForm = new InviteRecipient();
        $inviteForm->message = $message;

        if ($inviteForm->load(Yii::$app->request->post()) && $inviteForm->validate()) {
            foreach ($inviteForm->getRecipients() as $user) {
                if (version_compare(Yii::$app->version, '1.1', 'lt') || $user->getPermissionManager()->can(new SendMail())) {
                    // Attach User Message
                    $userMessage = new UserMessage();
                    $userMessage->message_id = $message->id;
                    $userMessage->user_id = $user->id;
                    $userMessage->is_originator = 0;
                    $userMessage->save();
                    $message->notify($user);
                }
            }
            return $this->htmlRedirect(['index', 'id' => $message->id]);
        }

        return $this->renderAjax('/mail/adduser', array('inviteForm' => $inviteForm));
    }

    /**
     * Used by user picker, searches user which are allowed messaging permissions
     * for the current user (v1.1).
     *
     * @return type
     */
    public function actionSearchUser()
    {
        Yii::$app->response->format = 'json';

        $group = [];
        //check contact user
        $contactUsers = Contact::findAll(['user_id' => Yii::$app->user->id]);
        if (count($contactUsers) != 0){
            foreach ($contactUsers as $contactUser){
                $group[] = $contactUser->contact_user_id;
            }
        }


        //check circle members
        $spaces = Membership::findAll(['user_id' => Yii::$app->user->id, 'status' => 3]);
        if (count($spaces) != 0){
            foreach ($spaces as $space){
                foreach (Membership::find()->where(['space_id' => $space])->andWhere(['<>', 'user_id', Yii::$app->user->id])->each() as $member){
                    $group[] = $member->user_id;
                }
            }
        }


//        Yii::getLogger()->log(array_unique($group), Logger::LEVEL_INFO, 'MyLog');
        return $this->getUserPickerResult(Yii::$app->request->get('keyword'), array_unique($group));
    }

    private function getUserPickerResult($keyword, $limit )
    {
        if (version_compare(Yii::$app->version, '1.1', 'lt')) {
            return $this->findUserByFilter($keyword, 10, $limit);
        } else if (Yii::$app->getModule('friendship')->getIsEnabled()) {
            return UserPicker::filter([
                'keyword' => $keyword,
                'permission' => new SendMail(),
                'fillUser' => true
            ]);
        } else {
            return UserPicker::filter([
                'keyword' => $keyword,
                'permission' => new SendMail()
            ]);
        }
    }

    /**
     * User picker search for adding additional users to a conversaion,
     * searches user which are allwed messaging permissions for the current user (v1.1).
     * Disables users already participating in a conversation.
     *
     * @return type
     */
    public function actionSearchAddUser()
    {
        Yii::$app->response->format = 'json';
        $message = $this->getMessage(Yii::$app->request->get('id'));

        if ($message == null) {
            throw new HttpException(404, 'Could not find message!');
        }

        $result = $this->getUserPickerResult(Yii::$app->request->get('keyword'));

        //Disable already participating users
        foreach ($result as $i => $user) {
            if ($this->isParticipant($message, $user)) {
                $result[$i++]['disabled'] = true;
            }
        }

        return $result;
    }

    /**
     * Checks if a user (user json representation) is participant of a given
     * message.
     *
     * @param type $message
     * @param type $user
     * @return boolean
     */
    private function isParticipant($message, $user)
    {
        foreach ($message->users as $participant) {
            if ($participant->guid === $user['guid']) {
                return true;
            }
        }
        return false;
    }

    /*
     * @deprecated
     */
    private function findUserByFilter($keyword, $maxResult, $limit)
    {

        $query = User::find()->limit($maxResult)->joinWith('profile');
//        Yii::getLogger()->log($keyword, Logger::LEVEL_INFO, 'MyLog');
        foreach (explode(" ", $keyword) as $part) {
//            $query->orFilterWhere(['like', 'user.email', $part]);
//            $query->orFilterWhere(['like', 'user.username', $part]);
            $query->orFilterWhere(['like', 'profile.firstname', $part]);
            $query->orFilterWhere(['like', 'profile.lastname', $part]);
//            $query->orFilterWhere(['like', 'profile.title', $part]);
            $query->andFilterWhere(['id' => $limit]);
        }

        $query->active();

        $results = [];
        foreach ($query->all() as $user) {

//            Yii::getLogger()->log($user->username, Logger::LEVEL_INFO, 'MyLog');
            if ($user != null) {

                $userInfo = array();
                $userInfo['guid'] = $user->guid;
                $userInfo['displayName'] = Html::encode($user->displayName);
                $userInfo['image'] = $user->getProfileImage()->getUrl();
                $userInfo['link'] = $user->getUrl();
                $results[] = $userInfo;

            }
        }
        return $results;
    }

    /**
     * Creates a new Message
     * and redirects to it.
     */
    public function actionCreate()
    {
        $userGuid = Yii::$app->request->get('userGuid');
//        Yii::getLogger()->log(print_r(Yii::$app->request->get('userGuid'),true),yii\log\Logger::LEVEL_INFO,'MyLog');

        $model = new CreateMessage();

        // Preselect user if userGuid is given
        if ($userGuid != "") {
            $user = User::findOne(['guid' => $userGuid]);
            if (isset($user) && (version_compare(Yii::$app->version, '1.1', 'lt') || $user->getPermissionManager()->can(new SendMail()))) {
                $model->recipient = $user->guid;
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            Yii::getLogger()->log(print_r($model->recipient,true),yii\log\Logger::LEVEL_INFO,'MyLog');
            // Create new Message
            $message = new Message();
            $message->title = $model->title;
            $message->save();

            // Attach Message Entry
            $messageEntry = new MessageEntry();
            $messageEntry->message_id = $message->id;
            $messageEntry->user_id = Yii::$app->user->id;
            $messageEntry->content = $model->message;
            $messageEntry->save();
            $messageEntry->notify();
            File::attachPrecreated($messageEntry, Yii::$app->request->post('fileUploaderHiddenGuidField'));



//            Yii::getLogger()->log($messageEntry->message_id, Logger::LEVEL_INFO, 'MyLog');
//            Yii::getLogger()->log($messageEntry->user_id, Logger::LEVEL_INFO, 'MyLog');
//            Yii::getLogger()->log($messageEntry->content, Logger::LEVEL_INFO, 'MyLog');
            sleep(1);


            // Attach also Recipients, send message to each recipient
            // 1.add data into database "user_message"
            // 2.send message through GCM to each recipient who have the device
            foreach ($model->getRecipients() as $recipient) {
                $userMessage = new UserMessage();
                $userMessage->message_id = $message->id;
                $userMessage->user_id = $recipient->id;
                $userMessage->save();


                $AllUserid = UserMessage::findAll(['message_id' =>$messageEntry->message_id]);
                foreach ($AllUserid as $test2)
                {
                    if ($test2->user_id != $messageEntry->user_id){
                        $users_tokenT = MobileToken::find()->where(['user_id' =>$test2->user_id ])->all();
                        if($users_tokenT != null)
                        {
                            foreach($users_tokenT as $users_tokenT) {
                                $mobile_token = $users_tokenT->device_token;
//                                $sendNot = new sendNotificationIOS();
//                                $sendNot ->sendMessage($mobile_token,$messageEntry->content);
                                $firebase = new Firebase();
                                $firebase->send($mobile_token,$messageEntry->content);
                            }
                        }

                    }
                }


//                if ($recipient->device_id != null){

                    $deviceMessage = new DeviceMessage();
                    $deviceMessage->type = "message,create";
                    $deviceMessage->message_id = $message->id;
                    $deviceMessage->user_id = $recipient->id;
                    $deviceMessage->from_id = Yii::$app->user->id;
                    $deviceMessage->content = $model->message;
                    $deviceMessage->notify();
//                }

            }

            // Inform recipients (We need to add all before)
            foreach ($model->getRecipients() as $recipient) {
                try {
                    $message->notify($recipient);
                } catch(\Exception $e) {
                    Yii::error('Could not send notification e-mail to: '. $recipient->username.". Error:". $e->getMessage());
                }

                //send notification to device
            }

            // Attach User Message
            $userMessage = new UserMessage();
            $userMessage->message_id = $message->id;
            $userMessage->user_id = Yii::$app->user->id;
            $userMessage->is_originator = 1;
            $userMessage->last_viewed = new \yii\db\Expression('NOW()');
            $userMessage->save();

            return $this->htmlRedirect(['index', 'id' => $message->id]);
        }

        return $this->renderAjax('create', array('model' => $model));
    }

    /**
     * Leave Message / Conversation
     *
     * Leave is only possible when at least two people are in the
     * conversation.
     */
    public function actionLeave()
    {
        $id = Yii::$app->request->get('id');
        $message = $this->getMessage($id);

        if ($message == null) {
            throw new HttpException(404, 'Could not find message!');
        }

        $message->leave(Yii::$app->user->id);

        if (Yii::$app->request->isAjax) {
            return $this->htmlRedirect(['index']);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Edits Entry Id
     */
    public function actionEditEntry()
    {
        $messageEntryId = (int)Yii::$app->request->get('messageEntryId');
        $entry = MessageEntry::findOne(['id' => $messageEntryId]);

        // Check if message entry exists and it´s by this user
        if ($entry == null || $entry->user_id != Yii::$app->user->id) {
            throw new HttpException(404, 'Could not find message entry!');
        }
        if ($entry->load(Yii::$app->request->post()) && $entry->validate()) {
            // ?
            //$entry->content = $_POST['MessageEntry']['content'];
            $entry->save();
            File::attachPrecreated($entry, Yii::$app->request->get('fileUploaderHiddenGuidField'));

            return $this->htmlRedirect(['index', 'id' => $entry->message->id]);
        }

        return $this->renderAjax('editEntry', array('entry' => $entry));
    }

    /**
     * Delete Entry Id
     *
     * Users can delete the own message entries.
     */
    public function actionDeleteEntry()
    {
        $this->forcePostRequest();

        $messageEntryId = (int)Yii::$app->request->get('messageEntryId');
        $entry = MessageEntry::findOne(['id' => $messageEntryId]);

        // Check if message entry exists and it´s by this user
        if ($entry == null || $entry->user_id != Yii::$app->user->id) {
            throw new HttpException(404, 'Could not find message entry!');
        }

        $entry->message->deleteEntry($entry);

        if (Yii::$app->request->isAjax) {
            return $this->htmlRedirect(['index', 'id' => $entry->message_id]);
        } else {
            return $this->redirect(['index', 'id' => $entry->message_id]);
        }
    }

    /**
     * Returns the number of new messages as JSON
     */
    public function actionGetNewMessageCountJson()
    {
        Yii::$app->response->format = 'json';

        $json = array();
        $json['newMessages'] = UserMessage::getNewMessageCount();

        return $json;
    }

    /**
     * Returns the Message Model by given Id
     * Also an access check will be performed.
     *
     * If insufficed privileges or not found null will be returned.
     *
     * @param int $id
     */
    private function getMessage($id)
    {
        $message = Message::findOne(['id' => $id]);

        if ($message != null) {

            $userMessage = UserMessage::findOne([
                'user_id' => Yii::$app->user->id,
                'message_id' => $message->id
            ]);
            if ($userMessage != null) {

                return $message;
            }
        }

        return null;
    }

    /**
     *
     */
    public function actionDevicecreate()
    {
//        $userGuid = Yii::$app->request->get('userGuid');
//        Yii::getLogger()->log(print_r(Yii::$app->user->id,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $model = new CreateMessage();
        $data = Yii::$app->request->post();
        $message_data = $data['CreateMessage'];
        $recipient_user_id = $message_data['recipient'];
        $user = User::findOne(['id' => $recipient_user_id]);
        $recipient = $user->guid;
        $title = $message_data['title'];
        $content = $message_data['message'];
        $model->recipient = $recipient;
        $model->title = $title;
        $model->message = $content;
        if ($model->validate()) {
            $message = new Message();
            $message->title = $model->title;
            $message->save();

            $messageEntry = new MessageEntry();
            $messageEntry->message_id = $message->id;
            $messageEntry->user_id = Yii::$app->user->id;
            $messageEntry->content = $model->message;
            $messageEntry->save();
            //$messageEntry->notify();
            File::attachPrecreated($messageEntry, Yii::$app->request->post('fileUploaderHiddenGuidField'));

            foreach ($model->getRecipients() as $recipient) {
                $userMessage = new UserMessage();
                $userMessage->message_id = $message->id;
                $userMessage->user_id = $recipient->id;
                $userMessage->save();

//                if ($recipient->device_id != null){


                $deviceMessage = new DeviceMessage();
                $deviceMessage->type = "message,create";
                $deviceMessage->message_id = $message->id;
                $deviceMessage->user_id = $recipient->id;
                $deviceMessage->from_id = Yii::$app->user->id;
                $deviceMessage->content = $model->message;
                $deviceMessage->notify();

                //echo "<script>console.log('". $deviceMessage ."');</script>";
                //echo $deviceMessage->user_id;
            }

            $deviceMessage = new DeviceMessage();
            $deviceMessage->type = "message,create";
            $deviceMessage->message_id = $message->id;
            $deviceMessage->user_id = $recipient->id;
            $deviceMessage->from_id = Yii::$app->user->id;
            $deviceMessage->content = $model->message;
            $deviceMessage->notify();
//            Yii::getLogger()->log($deviceMessage->message_id , Logger::LEVEL_INFO, 'MyLog');
//            Yii::getLogger()->log($deviceMessage->user_id , Logger::LEVEL_INFO, 'MyLog');
//            Yii::getLogger()->log($deviceMessage->from_id , Logger::LEVEL_INFO, 'MyLog');
//            Yii::getLogger()->log($deviceMessage->content , Logger::LEVEL_INFO, 'MyLog');

            $AllUserid = UserMessage::findAll(['message_id' => $deviceMessage->message_id]);
            $Allreceipt = array();
            foreach ($AllUserid as $test) {
                if ($test->user_id != $deviceMessage->from_id) {
                    $Allreceipt[] = $test->user_id;
                    $users_tokenT = MobileToken::find()->where(['user_id' => $test->user_id])->all();
                    if ($users_tokenT != null) {
                        foreach ($users_tokenT as $userToken) {
                            $mobile_token = $userToken->device_token;
//                            $sendNot = new sendNotificationIOS();
//                            $sendNot->sendMessage($mobile_token, $deviceMessage->content);
                            $firebase = new Firebase();
                            $firebase->send($mobile_token, $deviceMessage->content);
                        }
                    }
                }
            }
//                }


        }

        foreach ($model->getRecipients() as $recipient) {
            try {
                $message->notify($recipient);
            } catch (\Exception $e) {
                Yii::error('Could not send notification e-mail to: ' . $recipient->username . ". Error:" . $e->getMessage());
            }
        }

        $userMessage = new UserMessage();
        $userMessage->message_id = $message->id;
        $userMessage->user_id = Yii::$app->user->id;
        $userMessage->is_originator = 1;
        $userMessage->last_viewed = new \yii\db\Expression('NOW()');
        $userMessage->save();
    }

    public function actionDeviceread() {
//        Yii::getLogger()->log(print_r(Yii::$app->request->post(),true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $data = Yii::$app->request->post();
        $message_data = $data['ReadMessage'];
        $message_id = $message_data['message_id'];
        $user_id = Yii::$app->user->id;
        $userMessage = UserMessage::findOne(['message_id' => $message_id, 'user_id' => $user_id]);
        $userMessage->last_viewed = new \yii\db\Expression('NOW()');
        $userMessage->updated_at = new \yii\db\Expression('NOW()');
        $userMessage->updated_by = Yii::$app->user->id;
//        Yii::getLogger()->log(print_r($userMessage,true),yii\log\Logger::LEVEL_INFO,'MyLog');
        $userMessage->save();
    }

    public function actionDevicereply() {
        Yii::getLogger()->log(print_r('deviceReply',true),Logger::LEVEL_INFO,'MyLog');
        $data = Yii::$app->request->post();
        $message_data = $data['ReplyMessage'];
        $message_id = $message_data['message_id'];
        $content = $message_data['content'];
        $messageEntry = new MessageEntry();
        $messageEntry->message_id = $message_id;
        $messageEntry->user_id = Yii::$app->user->id;
        $messageEntry->content = $content;
        $messageEntry->created_at = new \yii\db\Expression('NOW()');
        $messageEntry->created_by = Yii::$app->user->id;
        $messageEntry->updated_at = new \yii\db\Expression('NOW()');
        $messageEntry->updated_by = Yii::$app->user->id;
        $messageEntry->save();
        $messageEntry->notify();

        $message = Message::findOne(['id' => $message_id]);
        $message->updated_at = new \yii\db\Expression('NOW()');
        $message->updated_by = Yii::$app->user->id;
        $message->save();

        $userMessage = UserMessage::findOne(['message_id' => $message_id, 'user_id' => Yii::$app->user->id]);
        $userMessage->last_viewed = new \yii\db\Expression('NOW()');
        $userMessage->updated_at = new \yii\db\Expression('NOW()');
        $userMessage->updated_by = Yii::$app->user->id;
        $userMessage->save();

        foreach (UserMessage::find()->where(['message_id' => $message_id])->each() as $userM) {
            $user = User::findOne(['id' => $userM->user_id]);
//            if ($user->device_id != null && $user->id != Yii::$app->user->id) {
            if ($user->id != Yii::$app->user->id) {
                $deviceMessage = new DeviceMessage();
                $deviceMessage->type = "message,reply";
                $deviceMessage->message_id = $message_id;
                $deviceMessage->user_id = $user->id;
                $deviceMessage->from_id = Yii::$app->user->id;
                $deviceMessage->content = $content;
                $deviceMessage->notify();

                $AllUserid = UserMessage::findAll(['message_id' => $deviceMessage->message_id]);
                $Allreceipt = array();
                foreach ($AllUserid as $test) {
                    if ($test->user_id != $deviceMessage->from_id) {
                        $Allreceipt[] = $test->user_id;
                        $users_tokenT = MobileToken::find()->where(['user_id' => $test->user_id])->all();
                        if ($users_tokenT != null) {
                            foreach ($users_tokenT as $userToken) {
                                $mobile_token = $userToken->device_token;
//                                $sendNot = new sendNotificationIOS();
//                                $sendNot->sendMessage($mobile_token, $deviceMessage->content);
                                $firebase = new Firebase();
                                $firebase->send($mobile_token, $deviceMessage->content);
                            }
                        }
                    }
                }
            }
        }

    }

}
