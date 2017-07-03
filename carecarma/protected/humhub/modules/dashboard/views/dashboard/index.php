<?php
$this->pageTitle = Yii::t('DashboardModule.views_dashboard_index', 'Home');
?>
<div class="container">
    <div class="row">
        <?php if (Yii::$app->user->id == 1): ?>
        <div class="col-md-8 layout-below-top-second">
        <?php else: ?>
        <div class="col-md-12 layout-below-top-second">
        <?php endif; ?>
            <?php
            if ($showProfilePostForm) {
                echo \humhub\modules\post\widgets\Form::widget(['contentContainer' => \Yii::$app->user->getIdentity()]);
            }
            ?>

            <?php
            echo \humhub\modules\content\widgets\Stream::widget([
                'streamAction' => '//dashboard/dashboard/stream',
                'showFilters' => false,
                'messageStreamEmpty' => Yii::t('DashboardModule.views_dashboard_index', '<b>Your home is empty!</b><br>Post something on your profile or join some circles!'),
            ]);
            ?>
        </div>
        <?php if (Yii::$app->user->id == 1): ?>
        <div class="col-md-4 layout-sidebar-container">
            <?php
            echo \humhub\modules\dashboard\widgets\Sidebar::widget(['widgets' => [
                    [\humhub\modules\activity\widgets\Stream::className(), ['streamAction' => '/dashboard/dashboard/stream'], ['sortOrder' => 150]]
            ]]);
            ?>
        </div>
        <?php endif; ?>
    </div>
</div>
