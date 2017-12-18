<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceState */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance State',
]) . $model->state_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->state_id, 'url' => ['view', 'id' => $model->state_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-state-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
