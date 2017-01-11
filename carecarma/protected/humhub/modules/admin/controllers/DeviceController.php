<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/14/2016
 * Time: 3:37 PM
 */

namespace humhub\modules\admin\controllers;


use humhub\compat\HForm;
use humhub\modules\admin\components\Controller;
use humhub\modules\admin\models\DeviceSearch;
use humhub\modules\user\models\Device;
use humhub\modules\user\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;

class DeviceController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new DeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', array(
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ));
    }

    public function actionAdd()
    {
        $deviceModel = new Device();

        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();

        // Add User Form
        $definition['elements']['Device'] = array(
            'type' => 'form',
            'elements' => array(
                'device_id' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 45,
                ),
                'gcmId' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'hardware_id' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 15,
                ),
                'phone' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'type' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'model' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'user_id' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 11,
                ),
            ),
        );

        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'label' => Yii::t('UserModule.controllers_DeviceController', 'Input device'),
            ),
        );

        $form = new HForm($definition);
        $form->models['Device'] = $deviceModel;

        if ($form->submitted('save') && $form->validate()) {
            $this->forcePostRequest();
            if ($form->models['Device']->save())
            {
                return $this->redirect(Url::toRoute('/admin/device'));
            }

        }

        return $this->render('add', array('hForm' => $form));

    }

    public function actionEdit()
    {
        $device = Device::findOne(['device_id' => Yii::$app->request->get('id')]);

        if ($device == null)
            throw new \yii\web\HttpException(404, Yii::t('AdminModule.controllers_DeviceController', 'Device not found!'));

        // Build Form Definition
        $definition = array();
        $definition['elements'] = array();
        // Add User Form
        $definition['elements']['Device'] = array(
            'type' => 'form',
            'elements' => array(
                'device_id'=> array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 45,
                    'readonly' => 'true',
                ),
                'gcmId' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'hardware_id' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 15,
                ),
                'phone' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'type' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'model' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 255,
                ),
                'user_id' => array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'maxlength' => 11,
                ),
                'activate' => array(
                    'type' => 'checkbox',
                ),
            )
        );


        // Get Form Definition
        $definition['buttons'] = array(
            'save' => array(
                'type' => 'submit',
                'label' => Yii::t('AdminModule.controllers_DeviceController', 'Save'),
                'class' => 'btn btn-primary',
            ),
            'delete' => array(
                'type' => 'submit',
                'label' => Yii::t('AdminModule.controllers_DeviceController', 'Delete'),
                'class' => 'btn btn-danger',

            ),
        );

        $form = new HForm($definition);
        $form->models['Device'] = $device;

        if ($form->submitted('save') && $form->validate()) {
            if ($form->save()) {
                return $this->redirect(Url::toRoute('/admin/device'));
            }
        }

        if ($form->submitted('delete')) {


            return $this->redirect(Url::toRoute(['/admin/device/delete', 'id' => $device->device_id]));
        }

        return $this->render('edit', array('hForm' => $form));


    }

    public function actionDelete()
    {
        $device = Device::findOne(['device_id' => Yii::$app->request->get('id')]);
        $doit = (int) Yii::$app->request->get('doit');


        if ($device == null) {
            throw new HttpException(404, Yii::t('AdminModule.controllers_DeviceController', 'Device not found!'));
        }

        if ($doit == 2){
            $this->forcePostRequest();

            $device->delete();

            return $this->redirect(Url::toRoute('/admin/device'));
        }

        return $this->render('delete', array(
            'model' => $device,
        ));

    }
}