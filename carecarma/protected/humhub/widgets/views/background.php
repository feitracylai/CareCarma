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

?>

<div class="config-wrapper">
        <div class="config-wrapper-inner">
            <a id="config-trigger" class="config-trigger" href="#"><i class="fa fa-cog"></i></a>
            <div id="config-panel" class="config-panel">
                <h5><strong>Select your background image</strong></h5>
                <!--<input value="<?php echo Yii::$app->user->id; ?>">
                <a href="#" onclick="javascript:$('#profilefileupload input').click();" class="btn btn-info btn-sm"><i
                        class="fa fa-cloud-upload"></i></a>-->
                <ul id="color-options" class="list-unstyled list-inline">
                    <li class="theme-1 <?php if($user->background == './uploads/background/1.jpg') echo 'active' ?>" >
<!--                        <a data-style="./uploads/background/1.jpg"></a>-->
                        <?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '1.jpg'])) ?>
                    </li>
                    <li class="theme-2 <?php if($user->background == './uploads/background/2.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '2.jpg'])) ?></li>
                    <li class="theme-3 <?php if($user->background == './uploads/background/3.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '3.jpg'])) ?></li>
                    <li class="theme-4 <?php if($user->background == './uploads/background/4.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '4.jpg'])) ?></li>
                    <li class="theme-5 <?php if($user->background == './uploads/background/5.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '5.jpg'])) ?></li>
                    <li class="theme-6 <?php if($user->background == './uploads/background/6.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '6.jpg'])) ?></li>
                    <li class="theme-7 <?php if($user->background == './uploads/background/7.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '7.jpg'])) ?></li>
                    <li class="theme-8 <?php if($user->background == './uploads/background/8.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '8.jpg'])) ?></li>
                    <li class="theme-9 <?php if($user->background == './uploads/background/9.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '9.jpg'])) ?></li>
                    <li class="theme-10 <?php if($user->background == './uploads/background/10.jpg') echo 'active' ?>"><?php echo Html::a('', Url::toRoute(['/user/account/upload', 'background' => '10.jpg'])) ?></li>
                </ul><!--//color-options-->
                <a id="config-close" class="close" href="#"><i class="fa fa-times-circle"></i></a>
            </div><!--//configure-panel-->
        </div><!--//config-wrapper-inner-->
    </div><!--//config-wrapper-->


<script>

    var style = '<?= $user->background; ?>';
    $(document).ready(function() {
//        var styleSheet = $('#color-options .active a').attr('data-style');
        if (style != ''){
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

//    $('#color-options a').on('click', function(e) {
//        var $listItem = $(this).closest('li');
//        if ($listItem.hasClass('active')){
//            $('#test').css("background", "#ebebeb");
//            $listItem.removeClass('active');
//            style = '';
//        }
//        else {
//            var styleSheet = $(this).attr('data-style');
//
//            $('#test').css("background", "#ebebeb url("+styleSheet+") no-repeat fixed");
//            $('#test').css("background-size", "cover");
//            style = styleSheet;
//
//
//            $listItem.addClass('active');
//            $listItem.siblings().removeClass('active');
//        }
//
//
//
////            e.preventDefault();
//
//    });
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