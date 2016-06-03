<?php
/**
 * User: wufei
 * Date: 5/12/2016
 * Time: 3:57 PM
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo Yii::t('UserModule.views_contact_connect', '<strong>Connect</strong> user to contact <u>{first} {last}</u>', array('{first}' => $contact->contact_first, '{last}' => $contact->contact_last)); ?>
        </div>

        <div class="panel-body">

            <!-- search form -->
            <?php echo Html::beginForm(Url::to(['/user/contact/connect']), 'get', array('class' => 'form-search')); ?>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="form-group form-group-search">
                        <?php echo Html::textInput("keyword", $keyword, array("class" => "form-control form-search", "placeholder" => Yii::t('UserModule.views_contact_connect', 'search for users'))); ?>
                        <?php echo Html::submitButton(Yii::t('UserModule.views_contact_connect', 'Search'), array('class' => 'btn btn-default btn-sm form-button-search')); ?>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
            <?php echo Html::endForm(); ?>

            <?php if (count($users) == 0): ?>
                <p><?php echo Yii::t('UserModule.views_contact_connect', 'No users found!'); ?></p>
            <?php endif; ?>
        </div>

        <hr>
        <ul class="media-list">
            <!-- BEGIN: Results -->
            <?php foreach ($users as $user) : ?>
                <!--            --><?php //echo Yii::t('UserModule.views_contact_import','user = {user}', array('{user}' => $user->id)); ?>

                <li>
                    <div class="media">

                        <!-- Follow Handling -->
                        <!--                    <div class="pull-right">
                        <?= \humhub\modules\user\widgets\UserFollowButton::widget(['user' => $user, 'followOptions' => ['class' => 'btn btn-primary btn-sm'], 'unfollowOptions' => ['class' => 'btn btn-info btn-sm']]); ?>
                   </div>-->
                        <div class="pull-right" >
                            <?php echo Html::a(Yii::t('UserModule.views_contact_connect', 'Connect'), Url::toRoute(['/user/contact/connect', 'id' => $contact->contact_id, 'doit' => 2, 'connect_id' => $user->id]), array('class' => 'btn btn-danger btn-xs pull-right', 'data-method' => 'POST', 'data-confirm' => 'Are you sure? Click "OK" if you want to connect this user account with your contact.')); ?>
                        </div>


                        <a href="#" class="pull-left contact" id="image-<?php echo $user->guid; ?>">
                            <img class="media-object img-rounded"
                                 src="<?php echo $user->getProfileImage()->getUrl(); ?>" width="50"
                                 height="50" alt="50x50" data-src="holder.js/50x50"
                                 style="width: 50px; height: 50px;">
                        </a>


                        <div class="media-body">
                            <h4 class="media-heading"><a
                                    href="<?php echo $user->getUrl(); ?>"><?php echo Html::encode($user->displayName); ?></a>

                            </h4>
                            <?php if ($user->group != null && $user->group->id != 1) { ?>
                                <small>(<?php echo Html::encode($user->group->name); ?>)</small><?php } ?>


                        </div>

                    </div>

<!--                    <div class="contactInfo" id="info---><?php //echo $user->guid; ?><!--" hidden>-->
<!--                        <hr>-->
<!--                        <div class="middle">-->
<!--                            --><?php //$form = \yii\widgets\ActiveForm::begin(); ?>
<!--                            --><?php
//                            $model->contact_user_id = $user->id;
//                            $model->contact_first = $user->profile->firstname;
//                            $model->contact_last = $user->profile->lastname;
//                            $model->contact_mobile = $user->profile->mobile;
//                            $model->contact_email = $user->email;
//
//                            ?>
<!--                            --><?php //echo $hForm->render($form); ?>
<!--                            --><?php //\yii\widgets\ActiveForm::end(); ?>
<!--                        </div>-->
<!---->
<!--                    </div>-->
                    <script type="text/javascript">
                        $('#image-<?php echo $user->guid; ?>').click(function(){

                            $('#info-<?php echo $user->guid; ?>').toggle();
                        })
                    </script>
                </li>

            <?php endforeach; ?>
            <!-- END: Results -->
        </ul>

    </div>
    <div class="pagination-container">
        <?php echo \humhub\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
    </div>


<?php
