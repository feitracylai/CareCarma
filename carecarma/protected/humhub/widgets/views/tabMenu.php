<?php
/**
 * Tab Navigation by MenuWidget.
 *
 * @package humhub.widgets
 * @since 0.5 */

use \yii\helpers\Html;
?>
<ul class="nav nav-pills">

    <?php foreach ($this->context->getItems() as $item) {?>
        <li <?php echo Html::renderTagAttributes($item['htmlOptions'])?>>
        <?php if ($item['icon'] != "") { ?>
        <?php echo Html::a($item['icon']."<span>".$item['label']."</span>", $item['url'], ['class' => 'tabmenu-back']); ?>
        <?php }else{ ?>
            <?php echo Html::a($item['label'], $item['url'], ['class' => 'tabmenu']); ?>
        <?php } ?>
    </li>
    <?php }; ?>
</ul>