<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */

$this->title = Module::t('modules/finance/app', 'Create Expenditure');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title . ' (' . Module::t('modules/finance/app', 'RCN') . ' ' .  $kae . ')';

?>
<div class="finance-expenditure-create">
	<?= $this->render('/default/infopanel');?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'expendwithdrawals_models' => $expendwithdrawals_models,
        'vat_levels' => $vat_levels,
        'kaewithdrawals' => $kaewithdrawals,
        'suppliers' => $suppliers,
        'expenddeduction_models' => $expenddeduction_models,
        'deductions' => $deductions,
        'withdrawals_expendituressum' => $withdrawals_expendituressum,
        'kae' => $kae
    ]) ?>

</div>
