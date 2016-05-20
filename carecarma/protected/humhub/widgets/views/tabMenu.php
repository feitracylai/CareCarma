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
        <?php echo Html::a($item['icon']."<span>".$item['label']."</span>", $item['url'], ['style' => 'border: 2px solid white; padding: 8px 13px; color: #ec6952; background-color: white']); ?>
        <?php }else{ ?>
            <?php echo Html::a($item['label'], $item['url']); ?>
        <?php } ?>
    </li>
    <?php }; ?>
</ul>