<?php
/**
 * Left Navigation by MenuWidget.
 *
 * @package humhub.widgets
 * @since 0.5
 */
?>

<!-- start: list-group navi for large devices -->
<div class="panel panel-default">
    <?php $count = 0;?>
    <?php foreach ($this->context->getItemGroups() as $group) : ?>

        <?php $items = $this->context->getItems($group['id']); ?>
        <?php if (count($items) == 0) continue; ?>

        <?php if ($group['label'] != "") : ?>
            <div class="panel-heading collapse-toggle hidden-md hidden-lg" type="button" data-toggle="collapse" data-parent="custom-collapse" data-target="#side_menu_<?php echo $count?>"><?php echo $group['label']; ?>  <i class="fa fa-angle-down"></i></div>
            <div class="panel-heading hidden-xs hidden-sm"><?php echo $group['label']; ?></div>

        <?php endif; ?>
        <div class="list-group pos-absolute collapse " id="side_menu_<?php echo $count?>">
            <?php foreach ($items as $item) : ?>
                <?php $item['htmlOptions']['class'] .= " list-group-item sub-item"; ?>
                <?php echo \yii\helpers\Html::a($item['icon']."<span>".$item['label']."</span>", $item['url'], $item['htmlOptions']); ?>
            <?php endforeach; ?>
            <?php echo "<hr>"; ?>
        </div>
        <?php ++$count;?>
    <?php endforeach; ?>


</div>
<!-- end: list-group navi for large devices -->
