<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TransportMode */

$this->title = Yii::t('app', 'Create Transport Mode');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transport Modes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-mode-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
