<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\notification\components;

use humhub\modules\dashboard\models\MobileToken;
use humhub\modules\dashboard\models\MobileTokenQuery;
use Yii;
use yii\base\ViewContextInterface;
use yii\helpers\Url;
use ReflectionClass;
use humhub\modules\notification\models\Notification;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentAddonActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\libs\Firebase;
use humhub\libs\sendNotificationIOS;
use yii\log\Logger;

/**
 * BaseNotification
 *
 * @author luke
 */
class BaseNotification extends \yii\base\Component implements ViewContextInterface
{

    const OUTPUT_WEB = 'web';
    const OUTPUT_MAIL = 'mail';
    const OUTPUT_MAIL_PLAINTEXT = 'mail_plaintext';
    const OUTPUT_TEXT = 'text';

    /**
     * User which created this notification.
     *
     * @var \humhub\modules\user\models\User
     */
    public $originator;

    /**
     * @var string
     */
    public $viewName = null;

    /**
     * Source of this notification
     * As example this can be a Space, Like or Post.
     *
     * @var \yii\db\ActiveRecord
     */
    public $source;
    /**
     * Space this notification belongs to. (Optional)
     * If source is a Content, ContentAddon or ContentContainer this will be
     * automatically set.
     *
     * @var \humhub\modules\space\models\Space
     */
    public $space;

    /**
     * Layout file for web version
     *
     * @var string
     */
    protected $layoutWeb = "@humhub/modules/notification/views/layouts/web.php";

    /**
     * Layout file for mail version
     *
     * @var string
     */
    protected $layoutMail = "@humhub/modules/notification/views/layouts/mail.php";

	/**
     * Layout file for mail plaintext version
     *
     * @var string
     */
    protected $layoutMailPlaintext = "@humhub/modules/notification/views/layouts/mail_plaintext.php";

    /**
     * The notification record this notification belongs to
     *
     * @var Notification
     */
    public $record;

    /**
     * @var string the module id which this notification belongs to (required)
     */
    public $moduleId = "";

    /**
     * @var boolean automatically mark notification as seen after click on it
     */
    public $markAsSeenOnClick = true;

    /**
     * Renders the notification
     *
     * @return string
     */
    public function render($mode = self::OUTPUT_WEB, $params = [])
    {
        $params['originator'] = $this->originator;
        $params['source'] = $this->source;
        $params['space'] = $this->space;
        $params['record'] = $this->record;
        $params['isNew'] = ($this->record->seen != 1);
        $params['url'] = Url::to(['/notification/entry', 'id' => $this->record->id], true);

        $viewFile = $this->getViewPath() . '/' . $this->viewName . '.php';

        // Switch to extra mail view file - if exists (otherwise use web view)
        if ($mode == self::OUTPUT_MAIL || $mode == self::OUTPUT_MAIL_PLAINTEXT) {
            $viewMailFile = $this->getViewPath() . '/mail/' . ($mode == self::OUTPUT_MAIL_PLAINTEXT ? 'plaintext/' : '') . $this->viewName . '.php';
            if (file_exists($viewMailFile)) {
                $viewFile = $viewMailFile;
            }
        } elseif ($mode == self::OUTPUT_TEXT) {
            $html = Yii::$app->getView()->renderFile($viewFile, $params, $this);
            return strip_tags($html);
        }

        $params['content'] = Yii::$app->getView()->renderFile($viewFile, $params, $this);

        return Yii::$app->getView()->renderFile(($mode == self::OUTPUT_WEB) ? $this->layoutWeb : ($mode == self::OUTPUT_MAIL_PLAINTEXT ? $this->layoutMailPlaintext : $this->layoutMail), $params, $this);
    }

    /**
     * Sends this notification to a set of users.
     *
     * @param mixed $users can be an array of User records or an ActiveQuery.
     */
    public function sendBulk($users)
    {
        if ($users instanceof \yii\db\ActiveQuery) {
            $users = $users->all();
        }

        foreach ($users as $user) {
            $this->send($user);
        }
    }

    /**
     * Sends this notification to a User
     *
     * @param User $user
     */
    public function send(User $user, $msg = null)
    {

        if ($this->moduleId == "") {
            throw new \yii\base\InvalidConfigException("No moduleId given!");
        }

        // Skip - do not set notification to the originator
        if ($this->originator !== null && $user->id == $this->originator->id) {
            return;
        }

        $users_token = MobileToken::find()->where(['user_id' => $user->id])->all();
	// Yii::getLogger()->log('sendFCM', Logger::LEVEL_INFO, 'MyLog');
        if($users_token != null)
        {
            if($msg == null) $msg = 'New Notification';

            foreach($users_token as $user_token) {
               $mobile_token = $user_token->device_token;
		// Yii::getLogger()->log($mobile_token, Logger::LEVEL_INFO, 'MyLog');
               $firebase = new Firebase(); //for Android device
               $firebase->send($mobile_token,$msg );
//                $sendNot = new sendNotificationIOS(); //for IOS device
//                $sendNot ->sendMessage($mobile_token,$msg);

               
            }
//            $firebase->send('cwcgLhC5Xsk:APA91bE0F9LUf3vaFqbS5qQYWi4RRg2UkDFxWb1Ah1raYX8nfIjAEFVOC6B43hL9TMEm49itOS8xHVlF_TXguJZXe6tfi3NMdOCDYOhmtVvkA8WAWdJwrHshqconL0dOITBL6PJ0Vnmk', 'App server');
        }

        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->class = $this->className();
        $notification->module = $this->moduleId;
        $notification->seen = 0;

        if ($this->source !== null) {
            $notification->source_pk = $this->source->getPrimaryKey();
            $notification->source_class = $this->source->className();

            // Automatically set spaceId if source is Content/Addon/Container
            if ($this->source instanceof ContentActiveRecord || $this->source instanceof ContentAddonActiveRecord) {
                if ($this->source->content->container instanceof \humhub\modules\space\models\Space) {
                    $notification->space_id = $this->source->content->container->id;
                }
            } elseif ($this->source instanceof \humhub\modules\space\models\Space) {
                $notification->space_id = $this->source->id;
            }
        }

        if ($this->originator !== null) {
            $notification->originator_user_id = $this->originator->id;
        }
        
        $notification->save();
    }

    /**
     * Deletes this notification
     */
    public function delete(\humhub\modules\user\models\User $user = null)
    {
        $condition = [];

        $condition['class'] = $this->className();

        if ($user !== null) {
            $condition['user_id'] = $user->id;
        }

        if ($this->originator !== null) {
            $condition['originator_user_id'] = $this->originator->id;
        }

        if ($this->source !== null) {
            $condition['source_pk'] = $this->source->getPrimaryKey();
            $condition['source_class'] = $this->source->className();
        }

        Notification::deleteAll($condition);
    }

    /**
     * Returns the directory containing the view fi
     * les for this notification.
     * The default implementation returns the 'views' subdirectory under the directory containing the notification class file.
     * @return string the directory containing the view files for this notification.
     */
    public function getViewPath()
    {
        $class = new ReflectionClass($this);
        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'views';
    }

    /**
     * Url of the origin of this notification
     * If source is a Content / ContentAddon / ContentContainer this will automatically generated.
     *
     * @return string
     */
    public function getUrl()
    {
        if ($this->source instanceof ContentActiveRecord || $this->source instanceof ContentAddonActiveRecord) {
            return $this->source->content->getUrl();
        } elseif ($this->source instanceof ContentContainerActiveRecord) {
            return $this->source->getUrl();
        }

        return "#";
    }

    /**
     * Build info text about a content
     *
     * This is a combination a the type of the content with a short preview
     * of it.
     *
     * @param Content $content
     * @return string
     */
    public function getContentInfo(\humhub\modules\content\interfaces\ContentTitlePreview $content)
    {
        return \yii\helpers\Html::encode($content->getContentName()) .
                ' "' .
                \humhub\widgets\RichText::widget(['text' => $content->getContentDescription(), 'minimal' => true, 'maxLength' => 60]) . '"';
    }

    /**
     * Marks notification as seen
     */
    public function markAsSeen()
    {
        $this->record->seen = 1;
        $this->record->save();
    }

    /**
     * Static initializer should be prefered over new initialization, since it makes use
     * of Yii::createObject dependency injection/configuration.
     *
     * @return \humhub\components\SocialActivity
     */
    public static function instance($options = [])
    {
        return Yii::createObject(static::class, $options);
    }
}
