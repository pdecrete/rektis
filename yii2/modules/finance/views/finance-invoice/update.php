<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoice */

$this->title = Module::t('modules/finance/app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
if($expenditures_return == 1)
    $this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures'), 'url' => ['/finance/finance-expenditure/']];
else
    $this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Vouchers'), 'url' => ['/finance/finance-invoice/']];
$this->params['breadcrumbs'][] = Module::t('modules/finance/app', 'Update Voucher');
?>
<div class="finance-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'invoice_model' => $invoice_model,
        'expenditure_model' => $expenditure_model,
        'supplier_model' => $supplier_model,
        'invoicetypes_model' => $invoicetypes_model
    ]) ?>

</div>
