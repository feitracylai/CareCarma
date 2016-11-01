<?php

use humhub\compat\CActiveForm;
use humhub\compat\CHtml;
use humhub\modules\user\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('UserModule.views_contact_edit', '<strong>Edit</strong> contact'); ?></div>
    <div class="panel-body">

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

            </div>


        <hr>
        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>