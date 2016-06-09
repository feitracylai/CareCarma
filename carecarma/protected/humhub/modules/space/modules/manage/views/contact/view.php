<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
use humhub\modules\user\models\User;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('UserModule.views_contact_delete', '<strong>View</strong> Contact'); ?></div>
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
        echo \yii\widgets\DetailView::widget([
            'model' => $contact,
            'attributes' => [
                'contact_first',
                'contact_last',
                'contact_mobile',
                'contact_email:email',
                'nickname',

            ],
        ]);
        ?>

        <br/>
        <?php echo Html::a(Yii::t('UserModule.views_contact_delete', 'Back'), Url::toRoute(['/space/manage/contact', 'Cid' => $contact->contact_id, 'id' => $contact->user_id, 'sguid' => $space->guid]), array('class' => 'btn btn-primary')); ?>


    </div>
</div>