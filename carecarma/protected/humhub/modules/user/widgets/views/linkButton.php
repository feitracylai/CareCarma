<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 7/7/2016
 * Time: 10:03 AM
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="btn-group">
    <?php echo Html::a(Yii::t('SpaceModule.widgets_views_membershipButton', 'Accept Link'), $user->createUrl('/user/contact/link-accept'), array('class' => 'btn btn-info', 'data-method' => 'POST')); ?>
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu">
        <li><?php echo Html::a(Yii::t('SpaceModule.widgets_views_membershipButton', 'Deny Link'), $user->createUrl('/user/contact/link-decline'), array('data-method' => 'POST')); ?></li>
    </ul>
</div>


