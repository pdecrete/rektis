<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TransportFunds */

$this->title = Yii::t('app', 'Create Transport Funds');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transport Funds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-funds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
