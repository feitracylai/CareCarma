<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\user\models\User;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_view', '<strong>View</strong> Contact'); ?>
    </div>
    <div class="panel-body">

        <?php if ($contact->contact_user_id != null){ ?>
            <?php $user = User::findOne(['id' => $contact->contact_user_id]) ?>
            <div class="media">

                <a href="#" class="pull-left contact" id="image-<?php echo $user->guid; ?>">
                    <img class="media-object img-rounded"
                         src="<?php echo $user->getProfileImage()->getUrl(); ?>" width="50"
                         height="50" alt="50x50" data-src="holder.js/50x50"
                         style="width: 50px; height: 50px;">
                </a>


                <div class="media-body">
                    <h4 class="media-heading"><a
                            href="#"><?php echo Html::encode($user->displayName); ?></a>
                    </h4>
                </div>
            </div>
            <hr>
        <?php } ?>

        <?php
        $relationship = Yii::$app->params['availableRelationship'];
        foreach ($relationship as $value) {
            if (is_array($value)){
                if (array_key_exists($contact->relation, $value)) {
                    $relation = $value[$contact->relation];
                }

            }

        }


        echo \yii\widgets\DetailView::widget([
            'model' => $contact,
            'attributes' => [
                'contact_first',
                'contact_last',
                'nickname',
                [
                    'label' => Yii::t('UserModule.views_contact_view','Relationship'),
                    'value' => $relation,
                ],
                'contact_mobile',
                'device_phone',
                'home_phone',
                'work_phone',
                'contact_email',


            ],
        ]);
        ?>

        <br/>
        <?php echo Html::a(Yii::t('UserModule.views_contact_view', 'Back'), Url::toRoute('/user/contact/index'), array('class' => 'btn btn-primary')); ?>

        <?php echo Html::a(Yii::t('UserModule.views_contact_view', 'Edit'), Url::toRoute(['/user/contact/edit', 'id' => $contact->contact_id]), array('class' => 'btn btn-primary pull-right')); ?>


    </div>
</div>