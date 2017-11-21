<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceYear */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Year',
]) . $model->year;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Years'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->year, 'url' => ['view', 'id' => $model->year]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-year-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
