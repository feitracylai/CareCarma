<?php

use humhub\compat\CActiveForm;
use humhub\compat\CHtml;
use humhub\modules\user\models\Contact;
use humhub\modules\user\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('UserModule.views_contact_edit', '<strong>Edit</strong> contact'); ?></div>
    <div class="panel-body">

        <?php if ($contact->contact_user_id != null){ ?>
            <?php $contactUser = User::findOne(['id' => $contact->contact_user_id]) ?>
            <div class="media">

                <a href="#" class="pull-left contact" id="image-<?php echo $contactUser->guid; ?>">
                    <img class="media-object img-rounded"
                         src="<?php echo $contactUser->getProfileImage()->getUrl(); ?>" width="50"
                         height="50" alt="50x50" data-src="holder.js/50x50"
                         style="width: 50px; height: 50px;">
                </a>


                <div class="media-body">
                    <h4 class="media-heading"><a
                            href="#"><?php echo Html::encode($contactUser->displayName); ?></a>
                    </h4>
                </div>

                <?php echo Html::a(Yii::t('UserModule.views_contact_edit', 'Disconnect'), $user->createUrl('disconnect', ['id' => $contact->contact_id]), array('class' => 'btn btn-danger btn-xs pull-right', 'data-method' => 'POST', 'data-confirm' => 'Are you sure? Click "OK" if you want to disconnect this user account with your contact.')); ?>
            </div>

        <?php }else{ ?>
            <?php echo Html::a(Yii::t('UserModule.views_contact_edit', 'Connect'), $user->createUrl('connect', ['id' => $contact->contact_id]), array('class' => 'btn btn-danger btn-xs pull-right')); ?>
        <?php } ?>
        <hr>
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>