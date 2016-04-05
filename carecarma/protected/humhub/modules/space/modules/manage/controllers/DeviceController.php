<?php


namespace humhub\modules\space\modules\manage\controllers;

use Yii;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
use humhub\modules\space\models\Space;
use humhub\modules\space\modules\manage\components\Controller;
use humhub\modules\space\modules\manage\models\DeviceUserSearch;
use humhub\modules\user\models\User;
use humhub\modules\space\models\Membership;
use humhub\libs\BasePermission;

/**
 * Member Controller
 *
 * @author Luke
 */
class DeviceController extends Controller
{

    /**
     * Members Administration Action
     */
    public function actionIndex()
    {
        $space = $this->getSpace();
        $searchModel = new DeviceUserSearch();
        $searchModel->space_id = $space->id;
        $searchModel->status = Membership::STATUS_MEMBER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // User Group Change
        if (Yii::$app->request->post('dropDownColumnSubmit')) {
            Yii::$app->response->format = 'json';
            $membership = Membership::findOne(['space_id' => $space->id, 'user_id' => Yii::$app->request->post('user_id')]);
            if ($membership === null) {
                throw new \yii\web\HttpException(404, 'Could not find membership!');
            }

            if ($membership->load(Yii::$app->request->post()) && $membership->validate() && $membership->save()) {
                return Yii::$app->request->post();
            }
            return $membership->getErrors();
        }

        return $this->render('index', array(
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'space' => $space
        ));
    }



}

?>
