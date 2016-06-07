<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model humhub\modules\user\models\beacon */

$this->title = 'Create Beacon';
$this->params['breadcrumbs'][] = ['label' => 'Beacons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beacon-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
