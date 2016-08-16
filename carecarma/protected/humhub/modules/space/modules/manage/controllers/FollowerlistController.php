<?php

namespace humhub\modules\space\modules\manage\controllers;

class FollowerlistController extends \humhub\modules\content\components\ContentContainerController
{
    public function actionIndex()
    {
        $space = $this->getSpace();
        $memberQuery = $space->getFollowers();
        $memberQuery->joinWith('user');
       // $memberQuery->where(['user.status' => \humhub\modules\user\models\User::STATUS_ENABLED]);
        return $this->render('index', ['space' => $this->space, 'members' => $memberQuery->all()]);
    }

}
