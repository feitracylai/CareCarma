<?php

namespace humhub\modules\user\controllers;



class FollowerlistController extends \humhub\modules\content\components\ContentContainerController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
