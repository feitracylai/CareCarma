<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
use humhub\modules\user\models\User;
?>

<?= CareEditMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_view', '<strong>{first} {last}</strong>', array('{first}' => $contact->contact_first, '{last}' => $contact->contact_last)); ?>
    </div>
    <div class="panel-body">

        <?php if ($contact->contact_user_id != null){ ?>
            <?php
            $user = User::findOne(['id' => $contact->contact_user_id]);
            $detail = 'col-sm-8';
            ?>
            <div class="media col-sm-4">

                <a href="<?php echo $user->getUrl(); ?>" class="pull- contact" id="image-<?php echo $user->guid; ?>">
                    <img class="media-object img-rounded"
                         src="<?php echo $user->getProfileImage()->getUrl(); ?>" width="100"
                         height="100" alt="100x100" data-src="holder.js/100x100"
                         style="width: 150px; height: 150px;">
                </a>



            </div>
        <?php }else {$detail = 'col-sm-12';} ?>

        <div class=<?php echo $detail; ?> >
            <?php
            $relationship = Yii::$app->params['availableRelationship'];
            foreach ($relationship as $value) {
                if (is_array($value)){
                    if (array_key_exists($contact->relation, $value)) {
                        $relation = $value[$contact->relation];
                    }

                }

            }
            echo \humhub\widgets\DetailView::widget([
                'model' => $contact,
                'attributes' => [
                    'nickname',
                    [
                        'label' => Yii::t('UserModule.views_contact_view','Relationship'),
                        'value' => $relation,
                    ],
                    'contact_mobile',
                    'device_phone',
                    'home_phone',
                    'work_phone',
                    'contact_email:email',

                ],
                'options' => [
                    'class' => 'table contact-view detail-view',
                    'style' => 'font-size: 15px; border-bottom: 2px solid #bebebe',
                ],
            ]);
            ?>
        </div>



        <div class="col-sm-12">
        <?php echo Html::a(Yii::t('UserModule.views_contact_delete', 'Back'), Url::toRoute(['/space/manage/contact', 'id' => $contact->user_id, 'sguid' => $space->guid]), array('class' => 'btn btn-primary')); ?>

        <?php echo Html::a(Yii::t('UserModule.views_contact_view', 'Edit'), Url::toRoute(['/space/manage/contact/edit', 'Cid' => $contact->contact_id, 'id' => $contact->user_id, 'sguid' => $space->guid]), array('class' => 'btn btn-primary pull-right')); ?>
        </div>
    </div>
</div>