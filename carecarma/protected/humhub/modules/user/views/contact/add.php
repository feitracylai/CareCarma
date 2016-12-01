<?php
/**
 * User: wufei
 * Date: 5/12/2016
 * Time: 3:57 PM
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \humhub\modules\space\models\Space;


Url::remember();




?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_add', '<strong>Add</strong> people'); ?>
    </div>

    <div class="panel-body">
<!--        --><?//=\humhub\modules\user\widgets\ContactMenu::widget(); ?>
<!--        <p/>-->
        <div class="center-block">
            <?php echo Html::a('<i class="fa fa-send"></i> '.Yii::t('UserModule.views_contact_add', 'Invite by e-mail'), $thisUser->createUrl('invite'), array('class' => 'btn btn-primary', 'data-target' => '#globalModal')); ?>
<!--            --><?php //echo Html::a('<i class="fa fa-send"></i> '.Yii::t('UserModule.views_contact_add', 'Import from Device'), $thisUser->createUrl('/user/contact/importlocal'), array('class' => 'btn btn-primary')); ?>

<!--        <div class="pull-right">-->
            <?php
            if (!$hideGoogle){
                echo Html::a('<img src="'.Yii::getAlias("@web").'/img/google.png" style="width:15px; margin-right: 5px">'.Yii::t('UserModule.views_contact_add', 'Import from Google'), "https://accounts.google.com/o/oauth2/auth?client_id=455820633290-p8i2kjqqtq1h9ve2p1qe63u3ed3ojlb5.apps.googleusercontent.com&redirect_uri=http://www.carecarma.com/carecarma/index.php?r=user%2Fcontact%2Fimportgoogle&scope=https://www.google.com/m8/feeds/&response_type=code", array('class' => 'btn btn-primary'));
            }

            ?>
        </div>
        </br>

        <!-- search form -->
        <?php echo Html::beginForm(Url::to(['/user/contact/add']), 'get', array('class' => 'form-search')); ?>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="form-group form-group-search">
                    <?php echo Html::textInput("keyword", $keyword, array("class" => "form-control form-search", "placeholder" => Yii::t('UserModule.views_contact_add', 'search for users'))); ?>
                    <?php echo Html::submitButton(Yii::t('UserModule.views_contact_add', 'Search'), array('class' => 'btn btn-default btn-sm form-button-search')); ?>
                    <?php if (strlen($keyword) == 1 || strlen($keyword) == 2):?>
                    <label style="color: red">Please type at least 3 words</label>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
        <?php echo Html::endForm(); ?>


        <?php $wholeusers = \humhub\modules\user\models\User::find()->count(); ?>
<!--        --><?php //Yii::getLogger()->log([count($users), $wholeusers], \yii\log\Logger::LEVEL_INFO, 'MyLog'); ?>
        <?php if (count($users) == 0 ){ ?>

            <p><?php echo Yii::t('UserModule.views_contact_add', 'No users found!'); ?></p>
        <?php }elseif($empty || strlen($keyword) == 1 || strlen($keyword) == 2){ ?>

        <?php }else{ ?>
    </div>

    <hr>
    <ul class="media-list">
        <!-- BEGIN: Results -->
        <?php foreach ($users as $user) : ?>

            <li>
                <div class="media" id="media-<?php echo $user->guid; ?>">

                    <div class="pull-right" >
                        <?php $contact = \humhub\modules\user\models\Contact::findOne(['user_id' => $thisUser->id, 'contact_user_id' => $user->id]);
                        if ($contact == null){
                            echo Html::a('<i class="fa fa-plus"></i> '.Yii::t('UserModule.views_contact_add', 'Add'), $thisUser->createUrl('/user/contact/add', ['doit' => 2, 'connect_id' => $user->id]), array('class' => 'btn btn-primary  pull-right', 'data-method' => 'POST'));
                        } elseif ($contact != null && $contact->linked == 0 ) { ?>

                        <a class="btn btn-default" disabled><i class="fa fa-plus"></i> Request Sent </a>&nbsp;
                        <?php echo Html::a(Yii::t('UserModule.views_contact_add', 'Cancel Request'),  Url::toRoute(['/user/contact/link-cancel', 'id' => $user->id]), array('class' => 'btn btn-danger pull-right'));
                        } else { ?>
                            <a class="btn btn-default" disabled><i class="fa fa-plus"></i> Already in Your List</a>&nbsp;
                        <?php } ?>
                    </div>


                    <a href="<?php echo $user->getUrl(); ?>" class="pull-left contact"">
                    <img class="media-object img-rounded"
                         src="<?php echo $user->getProfileImage()->getUrl(); ?>" width="50"
                         height="50" alt="50x50" data-src="holder.js/50x50"
                         style="width: 50px; height: 50px;">
                    </a>


                    <div class="media-body">
                        <a href="<?php echo $user->getUrl(); ?>">
                            <h4 class="media-heading">
                                <?php echo Html::encode($user->displayName); ?>

                            </h4>
                        </a>



                        <?php if ($details != null && isset($details[$user->id])) {?>
                            <a href="<?php echo $details[$user->id]->getUrl(); ?>">
                                <small >(<?php echo Yii::t('UserModule.views_contact_add', '{detail}', array('{detail}' => Space::findOne(['id' => $details[$user->id]])->name )); ?>)</small>

                            </a>
                        <?php } ?>




                    </div>

                </div>

                <script type="text/javascript">

                </script>


            </li>

        <?php endforeach; ?>
        <!-- END: Results -->
    </ul>

    <div class="pagination-container">
        <?php echo \humhub\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
    </div>
    <?php } ?>
</div>



