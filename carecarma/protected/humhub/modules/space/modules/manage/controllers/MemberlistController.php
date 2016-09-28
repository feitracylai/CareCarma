<?php

namespace humhub\modules\space\modules\manage\controllers;

class MemberlistController extends \humhub\modules\content\components\ContentContainerController
{
    public function actionIndex()
    {
        $space = $this->getSpace();
        $memberQuery = $space->getMemberships();
        $memberQuery->joinWith('user');
        $memberQuery->where(['user.status' => \humhub\modules\user\models\User::STATUS_ENABLED]);
        return $this->render('index', ['space' => $this->space, 'members' => $memberQuery->all()]);
    }
}
?>