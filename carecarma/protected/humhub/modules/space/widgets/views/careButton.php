<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 9/26/2016
 * Time: 4:20 PM
 */

use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use yii\helpers\Html;
?>

    <div class="btn-group">
        <?php echo Html::a(Yii::t('SpaceModule.widgets_views_careButton', 'Accept be Cared'), $space->createUrl('/space/manage/device/care-remind'), array('class' => 'btn btn-info', 'data-target' => '#globalModal')); ?>
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li><?php echo Html::a(Yii::t('SpaceModule.widgets_views_careButton', 'Deny be Cared'), $space->createUrl('/space/manage/device/care-denied'), array('data-method' => 'POST')); ?></li>
        </ul>
    </div>
    <?php
//} elseif ($membership->status == Membership::STATUS_APPLICANT) {
//    echo Html::a(Yii::t('SpaceModule.widgets_views_membershipButton', 'Cancel pending membership application'), $space->createUrl('/space/membership/revoke-membership'), array('class' => 'btn btn-primary', 'data-method' => 'POST'));
//}