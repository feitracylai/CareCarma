<?php

use humhub\modules\custom_pages\models\Page;
use humhub\modules\User\models\User;
?>

<?php if ($navigationClass == Page::NAV_CLASS_ACCOUNTNAV): ?>

    <iframe id="iframepage" style="width:100%; height: 400px;" src="<?php echo $url; ?>"></iframe>



    <style>
        #iframepage {
            border: none;
            background: url('<?php echo Yii::getAlias("@web/img/loader.gif"); ?>') center center no-repeat;
        }
    </style>

    <script>
        window.onload = function (evt) {
            setSize();
        }
        window.onresize = function (evt) {
            setSize();
        }

        function setSize() {

            $('#iframepage').css('height', window.innerHeight - 170 + 'px');
        }
    </script>


<?php else: ?>
    <?php
        $id = Yii::$app->user->id;
        $user = User::findOne(['id' => $id]);
        $email = $user->email;
    ?>
    <iframe name="iframework" src="about:blank" id="iframepage" style="width:100%;height:400px"></iframe>
    <script>
        var url = "<?php echo $url; ?>"
        var html = '<form action="<?php echo $url; ?>" method=post target="_self" id="data_form">' +
                   '<input type="hidden" name="email" value="<?php echo $email; ?>">' +
                   '<input type="hidden" name="password" value="11235813">' +
                   '</form>';
        document.getElementById('iframepage').contentWindow.document.write(html);
        document.getElementById('iframepage').contentWindow.document.getElementById('data_form').submit();
    </script>
<!--    <form id="myform" method="POST" action="--><?php //echo $url; ?><!--" target="iframework">-->
<!--        <input type="hidden" name="email" value="simonxu14@foxmail.com">-->
<!--        <input type="hidden" name="password" value="11235813">-->
<!--    </form>-->

    <style>
        #iframepage {
            position: absolute;
            left: 0;
            top: 150px;
            border: none;
            background: url('<?php echo Yii::getAlias("@web/img/loader.gif"); ?>') center center no-repeat;
        }
    </style>


    <script>
        window.onload = function (evt) {
            setSize();
        }
        window.onresize = function (evt) {
            setSize();
        }

        function setSize() {

            $('#iframepage').css('height', window.innerHeight - 100 + 'px');
            $('#iframepage').css('width', jQuery('body').outerWidth() - 1 + 'px');
        }
    </script>
<?php endif; ?>
