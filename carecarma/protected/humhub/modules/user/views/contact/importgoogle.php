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

\yii\helpers\Url::remember();

?>

<div class="panel panel-default">

    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_import', '<strong>Import</strong> from Google'); ?>
    </div>


    <ul class="media-list">
        <!-- BEGIN: Results -->
        <?php foreach ($data as $user) : ?>
            <?php if($thisUser->email == $user[2]){continue;}?>
            <li>
                <div class="media">

                    <div class="pull-right">
<!--                        --><?php //Yii::getLogger()->log(print_r($thisUser->createUrl('invite')."&googleemail=". $user[2],true),yii\log\Logger::LEVEL_INFO,'MyLog'); ?>
                        <?php
                            if ($user[3]=="0" && $user[4] == "0")
                                echo Html::a('<i class="fa fa-send"></i> '.Yii::t('UserModule.views_contact_add', 'Invite Contact'), $thisUser->createUrl('invite')."&googleemail=". $user[2], array('class' => 'btn btn-primary', 'data-target' => '#globalModal'));
                            else if ($user[3]=="1") {
                                $userAccount = User::findOne(['email' => $user[2]]);
                                $contact = \humhub\modules\user\models\Contact::findOne(['user_id' => $thisUser->id, 'contact_user_id' => $userAccount->id]);

                                if ($contact == null){
                                    echo Html::a('<i class="fa fa-plus"></i> '.Yii::t('UserModule.views_contact_add', 'Add in "People" lists'), $thisUser->createUrl('/user/contact/add', ['doit' => 2, 'connect_id' => $userAccount->id]), array('class' => 'btn btn-primary', 'data-method' => 'POST', 'title' => 'Add in "People" list'));

                                }else {
                                    echo "<a class='btn btn-default' disabled><i class='fa fa-user'></i> User already in CareCarma</a>";

                                }
//                                echo Html::a("<i class='fa fa-user'></i>".Yii::t('UserModule.views_contact_add', ' User already in CareCarma'), $userAccount->getUrl(), array('class' => 'btn btn-primary'));


                            }
                            else {
                                echo "<a class='btn btn-default' disabled><i class='fa fa-send'></i> Invite Sent</a>";
//                                echo Html::a(''.Yii::t('UserModule.views_contact_add', 'Invited'), "javascript:return false;", array('class' => 'btn btn-primary', 'data-target' => '#globalModal', 'disabled' => 'disabled'));
                            }
                        ?>
                    </div>
                    <?php if ($user[3]=="1"): $userAccount = User::findOne(['email' => $user[2]]);?>
                        <a href="<?php echo $userAccount->getUrl(); ?>" class="pull-left contact"">
                        <img class="media-object img-rounded"
                             src="<?php echo $userAccount->getProfileImage()->getUrl(); ?>" width="50"
                             height="50" alt="50x50" data-src="holder.js/50x50"
                             style="width: 50px; height: 50px;">
                        </a>

                    <?php endif; ?>

                    <div class="media-body">

                        <h4 class="media-heading">
                            <b><?php echo Html::encode($user[0]); ?></b>

                        </h4>
                        <?php echo Html::encode($user[2]); ?>
                    </div>
                </div>
            </li>

        <?php endforeach; ?>
        <!-- END: Results -->
    </ul>

</div>




