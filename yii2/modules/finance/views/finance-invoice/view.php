<?php

use app\modules\finance\Module;
use app\modules\finance\models\FinanceExpenditure;
use app\modules\finance\models\FinanceInvoicetype;
use app\modules\finance\models\FinanceSupplier;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoice */

$this->title = Module::t('modules/finance/app', 'View Voucher');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
if($expenditures_return == 1)
    $this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures'), 'url' => ['/finance/finance-expenditure/']];
else
    $this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Vouchers'), 'url' => ['/finance/finance-invoice/']];

$this->params['breadcrumbs'][] = $this->title;
/*
$unioned_model = array();
foreach($model as $key=>$value){
    $unioned_model[$key] = $value;;
}
$unioned_model['suppl_id'] = $supplier_model->suppl_name;
$unioned_model['invtype_id'] = $supplier_model->suppl_name;
echo "<pre>"; print_r($unioned_model); echo "</pre>";
*/
?>
<div class="finance-invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('modules/finance/app', 'Update'), ['update', 'id' => $model->inv_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('modules/finance/app', 'Delete'), ['delete', 'id' => $model->inv_id, 'expenditures_return' => $expenditures_return], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Module::t('modules/finance/app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        //'supplier_model' => $supplier_model,
        'attributes' => [
            ['attribute' => 'suppl_id',
                'value' => function ($model) {
                return FinanceSupplier::findOne(['suppl_id' => $model->suppl_id])['suppl_name'];
                }
            ],
            [
                'attribute' => Module::t('modules/finance/app', 'Amount'),
                'value' => function($model){
                                return Money::toCurrency(FinanceExpenditure::findOne(['exp_id' => $model->exp_id])['exp_amount'], true);}
            ],
            'inv_number',
            ['attribute' => 'inv_date',
             'format' => ['date', 'php:d-m-Y'],
            ],
            'inv_order',
            //'inv_deleted',
            ['attribute' => 'invtype_id',
                'value' => function ($model) {
                return FinanceInvoicetype::findOne(['invtype_id' => $model->invtype_id])['invtype_title'];
                }
            ],            
        ],
    ]) ?>

</div>
