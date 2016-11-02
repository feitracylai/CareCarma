<?php

use yii\helpers\Html;
?>

<div class="panel panel-default members" id="space-members-panel">


    <div class="panel-heading"><?php echo Yii::t('SpaceModule.widgets_views_spaceMembers', '<strong>Circle Members</strong>'); ?></div>
    <div class="panel-body">
        <?php foreach ($members as $membership) : ?>
            <?php $user = $membership->user; ?>
            <?php if($membership->status === \humhub\modules\space\models\Membership::STATUS_MEMBER) : ?>
                <div href="#" id="media-<?php echo $user->guid; ?>">
                    <img src="<?php echo $user->getProfileImage()->getUrl(); ?>" class="img-rounded tt img_margin"
                         style="width: 48px; height: 48px;" data-toggle="tooltip" data-placement="top" >
                    <strong><?php echo Html::encode($user->displayName); ?></strong>
<!--                    <hr>-->
                </div>

                <?php if ($space->isMember(Yii::$app->user->id)){ ?>

                <div class="contactInfo" id="info-<?php echo $user->guid; ?>" hidden>
                    <div class="middle">
                        <?php
                        $devicePhone = '';
                        if ($user->device_id != null){
                            $device = \humhub\modules\user\models\Device::findOne(['device_id' => $user->device_id]);
                            $devicePhone = $device->phone;
                        }
                            echo \humhub\widgets\DetailView::widget([
                                'model' => $user,
                                'attributes' => [
                                    'email:email',
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'Phone Home'),
                                        'value' => $user->profile->phone_private,
                                    ],
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'Phone Work'),
                                        'value' => $user->profile->phone_work,
                                    ],
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'Cosmos Phone'),
                                        'value' => $devicePhone,
                                    ],
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'Street'),
                                        'value' => $user->profile->street,
                                    ],
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'Apt/Unit'),
                                        'value' => $user->profile->street2,
//                                        'value' => $user->profile->address2,
                                    ],
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'City'),
                                        'value' => $user->profile->city,
                                    ],
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'State'),
                                        'value' => $user->profile->state,
                                    ],
                                    [
                                        'label' => Yii::t('SpaceModule.widgets_views_spaceMembers', 'Country'),
                                        'value' => $user->profile->country,
                                    ],
                                ],
                                'options' => [
                                    'class' => 'table member-view',
                                    'style' => 'margin: 10px 0; border-top: none',
                                ]
                            ]);
                        ?>
                    </div>
                </div>
                <script type="text/javascript">
                    $('#media-<?php echo $user->guid; ?>').click(function(){

                        $('#info-<?php echo $user->guid; ?>').toggle();

                    })
                </script>
                <?php } ?>

                <hr>
            <?php endif; ?>
        <?php endforeach; ?>


    </div>
</div>

