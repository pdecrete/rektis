<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoice */

$this->title = Yii::t('app', 'Create Finance Invoice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'invoice_model' => $invoice_model,
        'expenditure_model' => $expenditure_model,
        'supplier_model' => $supplier_model,
        'invoicetypes_model' => $invoicetypes_model
    ]) ?>

</div>
