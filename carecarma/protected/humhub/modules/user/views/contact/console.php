<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/12/2016
 * Time: 12:40 PM
 */

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\user\models\User;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_add', '<strong>Link</strong> console'); ?>
</div>


<div class="panel-body">
    <?=\humhub\modules\user\widgets\ContactMenu::widget(); ?>
    <p />

    <?php
        echo \humhub\widgets\GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'columns' => [
//                'user.profile.firstname',
//                'user.profile.lastname',
                [
                    'attribute' => 'user.profile.firstname',
                    'format' => 'raw',
                    'value' => function ($data) use ($user) {
                        if ($data->contact_user_id == $user->id) {
                            $contact_user = User::findOne(['id' => $data->user_id]);
                            return $contact_user->profile->firstname;
                        } else {
                            return $data->user->profile->firstname;
                        }
                    }
                ],
                [
                    'attribute' => 'user.profile.lastname',
                    'format' => 'raw',
                    'value' => function ($data) use ($user) {
                        if ($data->contact_user_id == $user->id) {
                            $contact_user = User::findOne(['id' => $data->user_id]);
                            return $contact_user->profile->lastname;
                        } else {
                            return $data->user->profile->lastname;
                        }
                    }
                ],
                [
                    'label' => Yii::t('UserModule.views_contact_console', 'Status'),
                    'format' => 'raw',
                    'value' =>
                        function ($data) use ($user) {
                            if ($data->user_id == $user->id) {
                                return Yii::t('UserModule.views_contact_console', 'Waiting for accept link...');
                            } elseif ($data->linked == 0){
                                return Yii::t('UserModule.views_contact_console', 'Need your accept');
                            } else {
                                return Yii::t('UserModule.views_contact_console', 'You are linked');
                            }

                        },

                ],

                [
                    'header' => 'Actions',
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['style' => 'width:120px; min-width:80px;'],
                    'buttons' => [
                        'update' => function($url, $model) use ($user) {
                            if ($model->contact_user_id == $user->id && $model->linked == 0)
                            {
                               $contactUser = User::findOne(['id' => $model->user_id]);
                                return Html::a('<i class="fa fa-check"></i>', Url::toRoute(['/user/contact/link-accept', 'uguid' => $contactUser->guid]), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'Accept']);
                            }
                            return;
                        },
                        'view' => function($url, $model) use ($user) {

                            if ($model->user_id == $user->id){
                                $contact_user = User::findOne(['id' => $model->contact_user_id]);
                            } else {
                                $contact_user = User::findOne(['id' => $model->user_id]);
                            }

                            return Html::a('<i class="fa fa-eye"></i>', $contact_user->getUrl(), ['class' => 'btn btn-primary btn-xs tt', 'title' => 'View']);
                        },

                        'delete' => function($url, $model)  {
                            return Html::a('<i class="fa fa-times"></i>', Url::toRoute(['/user/contact/link-cancel', 'id' => $model->contact_id]), ['class' => 'btn btn-danger btn-xs tt', 'data-confirm' => 'Are you sure?', 'data-method' => 'POST', 'title' => 'Cancel']);
                        },


                    ],
                ],

            ]
        ])

    ?>


</div>

</div>