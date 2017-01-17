<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TransportDistance */

$this->title = Yii::t('app', 'Create Transport Distance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transport Distances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-distance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
