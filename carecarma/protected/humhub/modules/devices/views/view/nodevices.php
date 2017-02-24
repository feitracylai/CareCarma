<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 2/2/2017
 * Time: 3:46 PM
 */

use humhub\widgets\GoogleChart;
use yii\helpers\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('DevicesModule.views_view_nodevices', '<strong>Health</strong> Report'); ?>
    </div>
    <div class="panel-body">

        <?php echo Yii::t('SpaceModule.views_admin_receiver', 'You do not activate a valid device (such as CoSMoS watch app, CareCarma watch app). Please activate '); ?>
        <?php echo Html::a('<u>here</u>', $user->createUrl('/user/account/edit-device'), array('style' => 'color: #4cacc6'));
        ?>
    </div>


</div>