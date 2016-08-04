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
use humhub\modules\space\modules\manage\widgets\CareEditMenu;
?>
<?= CareEditMenu::widget(['space' => $space]); ?>
    <br/>
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo Yii::t('UserModule.views_contact_connect', '<strong>Connect</strong> user to contact <u>{first} {last}</u>', array('{first}' => $contact->contact_first, '{last}' => $contact->contact_last)); ?>
        </div>

        <div class="panel-body">

            <!-- search form -->
            <?php echo Html::beginForm($space->createUrl('connect', ['Cid' => $contact->contact_id, 'rguid' => $receiver->guid]), 'get', array('class' => 'form-search')); ?>
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

            <?php $wholeusers = \humhub\modules\user\models\User::find()->count(); ?>
            <?php if (count($users) == 0 || count($users) == $wholeusers){ ?>
                <p><?php echo Yii::t('UserModule.views_contact_connect', 'No users found!'); ?></p>
            <?php }else{ ?>
        </div>

        <hr>
        <ul class="media-list">
            <!-- BEGIN: Results -->
            <?php foreach ($users as $user) : ?>
                <!--            --><?php //echo Yii::t('UserModule.views_contact_import','user = {user}', array('{user}' => $user->id)); ?>

                <li>
                    <div class="media">


                        <div class="pull-right" >
                            <?php echo Html::a(Yii::t('UserModule.views_contact_connect', 'Connect'), $space->createUrl('connect', ['Cid' => $contact->contact_id, 'doit' => 2, 'connect_id' => $user->id,'rguid' => $receiver->guid]), array('class' => 'btn btn-danger btn-xs pull-right', 'data-method' => 'POST', 'data-confirm' => 'Are you sure? Click "OK" if you want to connect this user account with your contact.')); ?>
                        </div>


                        <a class="pull-left contact" id="image-<?php echo $user->guid; ?>">
                            <img class="media-object img-rounded"
                                 src="<?php echo $user->getProfileImage()->getUrl(); ?>" width="50"
                                 height="50" alt="50x50" data-src="holder.js/50x50"
                                 style="width: 50px; height: 50px;">
                        </a>


                        <div class="media-body">
                            <h4 class="media-heading"><?php echo Html::encode($user->displayName); ?>

                            </h4>
                            <?php if ($details != null && $details[$user->id] != null && $details[$user->id] != 0) { ?>
                                <small>(<?php echo Yii::t('UserModule.views_contact_connect', '{detail}', array('{detail}' => Space::findOne(['id' => $details[$user->id]])->name )); ?>)</small>
                            <?php } ?>



                        </div>

                    </div>


                </li>

            <?php endforeach; ?>
            <!-- END: Results -->
        </ul>
        <?php } ?>
        <div class="panel-body">
            <?php echo Html::a(Yii::t('UserModule.views_contact_connect', '<i class="fa fa-backward"></i> Back'), $space->createUrl('edit', ['Cid' => $contact->contact_id, 'rguid' => $receiver->guid]), array('class' => 'btn btn-primary')); ?>
        </div>

    </div>
    <div class="pagination-container">
        <?php echo \humhub\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
    </div>


<?php
