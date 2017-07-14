<?php

use yii\helpers\Url;
use humhub\models\Setting;

?>
    <div class="panel panel-default panel-tour" id="getting-started-panel">
        <?php
        // Temporary workaround till panel widget rewrite in 0.10 verion
        $removeOptionHtml = "<li>" . \humhub\widgets\ModalConfirm::widget(array(
                'uniqueID' => 'hide-panel-button',
                'title' => '<strong>Remove</strong> tour panel',
                'message' => 'This action will remove this panel from your home page. You can reactivate it at<br>SETTINGS <i class="fa fa-caret-right"></i> Settings.',
                'buttonTrue' => 'Ok',
                'buttonFalse' => 'Cancel',
                'linkContent' => '<i class="fa fa-eye-slash"></i> ' . Yii::t('TourModule.widgets_views_tourPanel', ' Remove panel'),
                'linkHref' => Url::to(["/tour/tour/hide-panel", "ajax" => 1]),
                'confirmJS' => '$(".panel-tour").slideToggle("slow")'
            ), true) . "</li>";
        ?>

        <!-- Display panel menu widget -->
        <?php echo \humhub\widgets\PanelMenu::widget(array('id' => 'getting-started-panel', 'extraMenus' => $removeOptionHtml)); ?>

        <div class="panel-heading">
            <?php echo Yii::t('TourModule.widgets_views_tourPanel', '<strong>Tutorial</strong> Document'); ?>
        </div>
        <div class="panel-body">
            <p>
                <?php echo Yii::t('TourModule.widgets_views_tourPanel', 'Get to know everything about CareCarma with the following document:'); ?>
                <a href="/assets/CareCarma%20Tutorials.pdf" target="_blank" style="color: #6fdbe8"><u><strong>CareCarma Tutorials</strong></u></a>
            </p>




        </div>
    </div>

