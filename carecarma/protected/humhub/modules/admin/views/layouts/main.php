<div class="container">
    <div class="row">
        <div class="col-md-3 layout-below-top-second">
            <?= \humhub\modules\admin\widgets\AdminMenu::widget(); ?>
        </div>

        <div class="col-md-9">
            <?php echo $content; ?>
        </div>
    </div>
</div>