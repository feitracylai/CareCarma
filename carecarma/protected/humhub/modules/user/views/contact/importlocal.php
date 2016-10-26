<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/12/2016
 * Time: 10:07 AM
 */
use yii\helpers\Html;
use \humhub\modules\space\models\Space;
use yii\widgets\ActiveForm;
use humhub\compat\CHtml;
use humhub\modules\user\models\User;

?>

<div class="panel panel-default">

    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_import', '<strong>Import</strong> from Local'); ?>
    </div>


    <ul class="media-list">
        <!-- BEGIN: Results -->
        <?php foreach ($data as $user) : ?>

            <li>
                <div class="media">

                    <div class="pull-right">
                        <!--                        --><?php //Yii::getLogger()->log(print_r($thisUser->createUrl('invite')."&googleemail=". $user[2],true),yii\log\Logger::LEVEL_INFO,'MyLog'); ?>
<!--                        --><?php //echo Html::a('<i class="fa fa-send"></i> '.Yii::t('UserModule.views_contact_add', 'Invite Contact'), $thisUser->createUrl('invite')."&googleemail=". $user[1], array('class' => 'btn btn-primary', 'data-target' => '#globalModal')); ?>
                    </div>


                    <div class="media-body">
                        <h4 class="media-heading">
                            <b><?php echo Html::encode($user[0]); ?></b>

                        </h4>
                        <p><?php echo Html::encode($user[1]); ?></p>
                        <p><?php echo Html::encode($user[2]); ?></p>
                    </div>
                </div>
            </li>

        <?php endforeach; ?>
        <!-- END: Results -->
    </ul>

</div>




