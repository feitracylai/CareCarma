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
            <?php } elseif ($item['label'] == Yii::t('DashboardModule.base', '&nbsp&nbsp&nbsp&nbspHome&nbsp&nbsp&nbsp')){ ?>
                <span id="badge-notifications" style="display:none;" class="label label-danger label-notification visible-lg visible-md">1</span>
            <?php } ?>
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
 ?>



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

