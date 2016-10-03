<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 8/25/2016
 * Time: 2:52 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;

if (!Yii::$app->user->isGuest){
    $this->registerJsFile('@web/resources/user/profileHeaderImageUpload.js');
    $this->registerJs("var backgroundImageUploaderUrl='" . Url::to(['/user/account/background-image-upload', 'userGuid' => $user->guid]) . "';", \yii\web\View::POS_BEGIN);
}


$theme = \humhub\models\Setting::Get('theme');


?>

<div class="config-wrapper">
        <div class="config-wrapper-inner">
            <a id="config-trigger" class="config-trigger" href="#" title="template settings"><i class="fa fa-photo"></i></a>

            <div id="config-panel" class="config-panel">
                <h5 style="text-align: center"><strong>Template Settings</strong></h5><hr>

                <div id="config-content">
                    <h6><strong>Themes Color</strong></h6>
                    <ul id="theme-options" class="list-unstyled list-inline">
                        <li id="theme-1" data-style="theme-1.css" class="<?php if($user->theme == 'theme-1.css' || $user->theme == null) echo 'active' ?>"><a style="background-color: #4CACC6;"></a></li>
                        <li id="theme-2" data-style="theme-2.css" class="<?php if($user->theme == 'theme-2.css') echo 'active' ?>"><a style="background-color: #ec6952;"></a></li>
                        <li id="theme-3" data-style="theme-3.css" class="<?php if($user->theme == 'theme-3.css') echo 'active' ?>"><a style="background-color: #f89d29;"></a></li>
                        <li id="theme-4" data-style="theme-4.css" class="<?php if($user->theme == 'theme-4.css') echo 'active' ?>"><a style="background-color: #519f4b;"></a></li>
                        <li id="theme-5" data-style="theme-5.css" class="<?php if($user->theme == 'theme-5.css') echo 'active' ?>"><a style="background-color: #926ecd;"></a></li>
                        <li id="theme-6" data-style="theme-6.css" class="<?php if($user->theme == 'theme-6.css') echo 'active' ?>"><a style="background-color: #0381d1;"></a></li>
                        <li id="theme-7" data-style="theme-7.css" class="<?php if($user->theme == 'theme-7.css') echo 'active' ?>"><a style="background-color: #e89bbc;"></a></li>
                        <li id="theme-8" data-style="theme-8.css" class="<?php if($user->theme == 'theme-8.css') echo 'active' ?>"><a style="background-color: #25303f;"></a></li>

                    </ul>

                    <h6><strong>Customize</strong></h6>
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
                    <h6 style="padding-top: 10px"><strong>Template</strong></h6>
                    <ul id="background-options" class="list-unstyled list-inline">
                        

                        <?php for ($count = 1; $count <= 60; $count++) {?>
                            <li class="background-<?php echo $count; ?> <?php if($user->background == './uploads/background/'.$count.'.jpg') echo 'active' ?>">
                                <!--<?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => $count.'.jpg']), ['style' => 'background:#fff url(\'./uploads/background/'.$count.'.jpg\') no-repeat; background-size:cover']) ?>-->
                                <a data-style="<?php echo $count; ?>.jpg" style="background:#fff url('./uploads/background/<?php echo $count; ?>.jpg') no-repeat; background-size:cover"></a>
                            </li>
                        <?php } ?>
                    </ul><!--//background-options-->

                </div>




                <a id="config-close" class="close" href="#"><i class="fa fa-times-circle"></i></a>
            </div><!--//configure-panel-->
        </div><!--//config-wrapper-inner-->
    </div><!--//config-wrapper-->


<script>

    $(document).ready(function() {
//        var styleSheet = $('#background-options .active a').attr('data-style');
        var userbackground = '<?= $user->getUserBackgroundImage()->getUrl(); ?>';
        var defaultImage = '<?= $defaultImage; ?>';
        var style = '<?= $user->background; ?>';


        if (userbackground != '' && !defaultImage){
            $('#test').css("background", "#ebebeb url("+userbackground+") no-repeat fixed");
            $('#test').css("background-size", "cover");
        } else if (style != ''){
            $('#test').css("background", "#ebebeb url("+style+") no-repeat fixed");
            $('#test').css("background-size", "cover");

        }
        var height = $(window).height()-214;
        $('#config-content').css('height', height+'px');


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



    $('#theme-options a').on('click', function(e){
        var $liItem = $(this).closest('li');
        var $colorSheet = $liItem.attr('data-style');
        var $link = '<?php echo Yii::getAlias("@web"); ?>/themes/<?php echo $theme; ?>/css/';


        $.post('<?php echo Url::to(['/user/account/theme-save', 'userGuid' => $user->guid]); ?>', {'data': $colorSheet}, function(data){


            $('head').append('<link class="theme-choose" href='+$link+$colorSheet+' rel="stylesheet" />');


            $liItem.addClass('active');
            $liItem.siblings().removeClass('active');
        });



        e.preventDefault();
    });

    $('#background-options a').on('click', function(e){
        var $list  = $(this).closest('li');
        var $backgroundImage = $(this).attr('data-style');

        $.post('<?php echo Url::to(['/user/account/background']);  ?>', {'image': $backgroundImage}, function(data){
            $('#test').css("background", "#ebebeb url(./uploads/background/"+$backgroundImage+") no-repeat fixed");
            $('#test').css("background-size", "cover");

            $list.addClass('active');
            $list.siblings().removeClass('active');
        });

        e.preventDefault();
    });



</script>

