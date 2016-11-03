<?php

use yii\helpers\Html;
use yii\helpers\Url;


?>
<?php foreach ($this->context->getItems() as $item) : ?>
    <li class="hidden-xs <?php if ($item['isActive']): ?>active<?php endif; ?> <?php
    if (isset($item['id'])) {
        echo $item['id'];
    }
    ?>">
            <?php
//            if (!isset($item['img'])){
                echo Html::a($item['icon'] . "<br />" . $item['label'], $item['url'], $item['htmlOptions']);
//            } else {
//                echo Html::a($item['img'] . $item['label'], $item['url'], $item['htmlOptions']);
//            }
            if ($item['label'] == Yii::t('MailModule.base', 'Messages')){ ?>
                <span id="badge-messages" style="display:none;"
                      class="label label-danger label-notification">1</span>
            <?php }
             ?>
    </li>
<?php endforeach; ?>

<?php foreach ($this->context->getItems() as $item) : ?>
    <li class="visible-xs" <?php if ($item['isActive']): ?>active<?php endif; ?> <?php
    if (isset($item['id'])) {
        echo $item['id'];
    }
    ?>">
    <?php
    echo Html::a($item['icon'] . "<br />" , $item['url'], $item['htmlOptions']);
    if ($item['label'] == Yii::t('MailModule.base', 'Messages')){ ?>
        <span id="badge-messages-mobile" style="display:none;"
              class="label label-danger label-notification">1</span>
    <?php } ?>



    </li>
<?php endforeach; ?>

<!--<li class="dropdown visible-xs visible-sm">
    <a href="#" id="top-dropdown-menu" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-align-justify"></i><br>
        <?php /*echo Yii::t('base', 'Menu'); */?>
        <b class="caret"></b></a>
    <ul class="dropdown-menu">

        <?php /*foreach ($this->context->getItems() as $item) : */?>
            <li class="<?php /*if ($item['isActive']): */?>active<?php /*endif; */?>">
                <?php /*echo Html::a($item['label'], $item['url'], $item['htmlOptions']); */?>
            </li>
        <?php /*endforeach; */?>

    </ul>
</li>-->

<script type="text/javascript">


    setMailMessageCount(<?php echo \humhub\modules\mail\models\UserMessage::getNewMessageCount(); ?>);


    /**
     * Sets current message count
     */
    function setMailMessageCount(count) {
        // show or hide the badge for new messages
        if (count == 0) {
            $('#badge-messages').css('display', 'none');
            $('#badge-messages-mobile').css('display', 'none');
        } else {
            $('#badge-messages').empty();
            $('#badge-messages').append(count);
            $('#badge-messages').fadeIn('fast');

            $('#badge-messages-mobile').empty();
            $('#badge-messages-mobile').append(count);
            $('#badge-messages-mobile').fadeIn('fast');
        }
    }



</script>