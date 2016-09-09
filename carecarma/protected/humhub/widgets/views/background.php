<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 8/25/2016
 * Time: 2:52 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;

//Yii::getLogger()->log(Url::current(), \yii\log\Logger::LEVEL_INFO, 'MyLog');
Url::remember(Url::current());

$this->registerJsFile('@web/resources/user/profileHeaderImageUpload.js');
$this->registerJs("var backgroundImageUploaderUrl='" . Url::to(['/user/account/background-image-upload', 'userGuid' => $user->guid]) . "';", \yii\web\View::POS_BEGIN);


?>

<div class="config-wrapper">
        <div class="config-wrapper-inner">
            <a id="config-trigger" class="config-trigger" href="#"><i class="fa fa-cog"></i></a>
            <div id="config-panel" class="config-panel">
                <h5 style="text-align: center"><strong>Change Background Image</strong></h5><hr>
                <!--<input value="<?php echo Yii::$app->user->id; ?>">
                <a href="#" onclick="javascript:$('#profilefileupload input').click();" class="btn btn-info btn-sm"><i
                        class="fa fa-cloud-upload"></i></a>-->
                <h6><strong>Update</strong></h6>
                <div class="image-upload-container profile-user-photo-container" id="uploadcontent">

                    <?php

                    $profileImageExt = pathinfo($user->getUserBackgroundImage()->getUrl(), PATHINFO_EXTENSION);

                    $profileImageOrig = preg_replace('/.[^.]*$/', '', $user->getUserBackgroundImage()->getUrl());
                    $defaultImage = (basename($user->getUserBackgroundImage()->getUrl()) == 'default_background.jpg' || basename($user->getUserBackgroundImage()->getUrl()) == 'default_background.jpg?cacheId=0') ? true : false;
                    $profileImageOrig = $profileImageOrig . '_org.' . $profileImageExt;

                    if (!$defaultImage) {
                        ?>

                        <!-- background image output-->
                        <a data-toggle="lightbox" data-gallery="" href="<?php echo $profileImageOrig; ?>#.jpeg"
                           data-footer='<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.widgets_views_showFiles', 'Close'); ?></button>'>
                            <img class="img-rounded profile-user-photo" id="user-background-image"
                                 src="<?php echo $user->getUserBackgroundImage()->getUrl(); ?>"
                                 data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;"/>
                        </a>

                    <?php } else { ?>

                        <img class="img-rounded profile-user-photo" id="user-background-image"
                             src="<?php echo $user->getUserBackgroundImage()->getUrl(); ?>"
                             data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;"/>

                    <?php } ?>

                    <!-- check if the current user is the profile owner and can change the images -->

                        <form class="fileupload" id="userbackgroundupload" action="" method="POST" enctype="multipart/form-data"
                              style="position: absolute; top: 0; left: 10px; opacity: 0; height: 200px; width: 300px;">
                            <input type="file" name="backgroundfiles[]" style="height: 200px; width: 300px;">
                        </form>

                        <div class="image-upload-loader" id="background-image-upload-loader" style="padding-top: 60px;">
                            <div class="progress image-upload-progess-bar" id="background-image-upload-bar">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="00"
                                     aria-valuemin="0"
                                     aria-valuemax="100" style="width: 0%;">
                                </div>
                            </div>
                        </div>

                        <div class="image-upload-buttons" id="background-image-upload-buttons">
                            <a href="#" onclick="javascript:$('#userbackgroundupload input').click();" class="btn btn-info btn-sm"><i
                                    class="fa fa-cloud-upload"></i></a>
                            <!--<a id="profile-image-upload-edit-button"
                               style="<?php
                               if (!$user->getUserBackgroundImage()->hasImage()) {
                                   echo 'display: none;';
                               }
                               ?>"
                               href="<?php echo Url::to(['/user/account/crop-profile-image', 'userGuid' => $user->guid]); ?>"
                               class="btn btn-info btn-sm" data-target="#globalModal"><i
                                    class="fa fa-edit"></i></a>-->
                            <?php
                            echo \humhub\widgets\ModalConfirm::widget(array(
                                'uniqueID' => 'modal_backgroundimagedelete',
                                'linkOutput' => 'a',
                                'title' => Yii::t('UserModule.widgets_views_deleteImage', '<strong>Confirm</strong> image deleting'),
                                'message' => Yii::t('UserModule.widgets_views_deleteImage', 'Do you really want to delete your background image?'),
                                'buttonTrue' => Yii::t('UserModule.widgets_views_deleteImage', 'Delete'),
                                'buttonFalse' => Yii::t('UserModule.widgets_views_deleteImage', 'Cancel'),
                                'linkContent' => '<i class="fa fa-times"></i>',
                                'cssClass' => 'btn btn-danger btn-sm',
                                'style' => $user->getUserBackgroundImage()->hasImage() ? '' : 'display: none;',
                                'linkHref' => Url::to(["/user/account/delete-profile-image", 'type' => 'background', 'userGuid' => $user->guid]),
                                'confirmJS' => 'function(jsonResp) { resetProfileImage(jsonResp); }'
                            ));
                            ?>
                        </div>


                </div>



                <h6 style="padding-top: 10px"><strong>Select</strong></h6>
                <ul id="color-options" class="list-unstyled list-inline">

                    <?php for ($count = 1; $count <= 66; $count++) {?>
                        <li class="theme-<?php echo $count; ?> <?php if($user->background == './uploads/background/'.$count.'.jpg') echo 'active' ?>">
                            <?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => $count.'.jpg']), ['style' => 'background:#fff url(\'./uploads/background/'.$count.'.jpg\') no-repeat; background-size:cover']) ?>
                        </li>
                    <?php } ?>
                </ul><!--//color-options-->
                <a id="config-close" class="close" href="#"><i class="fa fa-times-circle"></i></a>
            </div><!--//configure-panel-->
        </div><!--//config-wrapper-inner-->
    </div><!--//config-wrapper-->


<script>

    var userbackground = '<?= $user->getUserBackgroundImage()->getUrl(); ?>';
    var defaultImage = '<?= $defaultImage; ?>';
    var style = '<?= $user->background; ?>';


    $(document).ready(function() {
//        var styleSheet = $('#color-options .active a').attr('data-style');
        if (userbackground != '' && !defaultImage){
            $('#test').css("background", "#ebebeb url("+userbackground+") no-repeat fixed");
            $('#test').css("background-size", "cover");
        } else if (style != ''){
            $('#test').css("background", "#ebebeb url("+style+") no-repeat fixed");
            $('#test').css("background-size", "cover");

        }



    })

    $('#config-trigger').on('click', function(e) {
        var $panel = $('#config-panel');
        var panelVisible = $('#config-panel').is(':visible');
        if (panelVisible) {
            $panel.hide();
        } else {
            $panel.show();
        }
        e.preventDefault();
    });

    $('#config-close').on('click', function(e) {
        e.preventDefault();
        $('#config-panel').hide();
    });


</script>


<style>

    .config-panel #color-options li.theme-1 a {
        background: #fff url('./uploads/background/1.jpg') no-repeat;
        background-size: cover;
    }
    .config-panel #color-options li.theme-2 a {
        background: #fff url('./uploads/background/2.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-3 a {
        background: #fff url('./uploads/background/3.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-4 a {
        background: #fff url('./uploads/background/4.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-5 a {
        background: #fff url('./uploads/background/5.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-6 a {
        background: #fff url('./uploads/background/6.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-7 a {
        background: #fff url('./uploads/background/7.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-8 a {
        background: #fff url('./uploads/background/8.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-9 a {
        background: #fff url('./uploads/background/9.jpg') no-repeat ;
        background-size: cover;
    }
    .config-panel #color-options li.theme-10 a {
        background: #fff url('./uploads/background/10.jpg') no-repeat ;
        background-size: cover;
    }

</style>
-->