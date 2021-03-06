<?php
/**
 * User: wufei
 * Date: 5/11/2016
 * Time: 1:29 PM
 */

use humhub\modules\space\modules\manage\widgets\DeviceReportMenu;
use humhub\widgets\GoogleChart;
?>

<?= DeviceReportMenu::widget(['space' => $space]); ?>
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('SpaceModule.views_admin_receiver', '<strong>{first} {last}</strong> report1', array('{first}' => $user->profile->firstname, '{last}' => $user->profile->lastname)); ?>
    </div>
    <div class="panel-body">

        <?php echo Yii::t('SpaceModule.views_admin_receiver', 'This person does not activate a valid device (such as CoSMoS watch app, CareCarma watch app).'); ?>
    </div>


</div>

