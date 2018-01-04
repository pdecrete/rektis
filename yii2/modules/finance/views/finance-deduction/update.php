<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceDeduction */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Deduction',
]) . $model->deduct_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Deductions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->deduct_id, 'url' => ['view', 'id' => $model->deduct_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-deduction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
