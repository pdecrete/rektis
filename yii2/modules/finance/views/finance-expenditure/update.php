<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Expenditure',
]) . $model->exp_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Expenditures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->exp_id, 'url' => ['view', 'id' => $model->exp_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-expenditure-update">
	
	<?= $this->render('/default/infopanel');?>
    
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'expendwithdrawals_models' => $expendwithdrawals_models,
        'vat_levels' => $vat_levels,
        'kaewithdrawals' => $kaewithdrawals,
        'suppliers' => $suppliers,
        'expenddeduction_models' => $expenddeduction_models,
        'deductions' => $deductions
    ]) ?>

</div>
