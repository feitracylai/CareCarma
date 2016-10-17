<?php
/**
 * Created by PhpStorm.
 * User: wufei
 * Date: 10/12/2016
 * Time: 10:07 AM
 */

use yii\widgets\ActiveForm;
use humhub\compat\CHtml;
use humhub\modules\user\models\User;

?>

<div class="panel panel-default">

    <div class="panel-heading">
        <?php echo Yii::t('UserModule.views_contact_test', '<strong>Test</strong>'); ?>
    </div>


    <div class="panel-body">
        <?=\humhub\modules\user\widgets\ContactMenu::widget(); ?>
    </div>

</div>




