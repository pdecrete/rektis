<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = Module::t('modules/finance/app', 'Vouchers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-invoice-index">
	<?= $this->render('/default/infopanel');?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'inv_number', 
             'label' => Module::t('modules/finance/app', 'Invoice number'),
            ],
            ['attribute' => 'inv_date', 
             'label' => Module::t('modules/finance/app', 'Invoice date')],
            ['attribute' => 'inv_order', 
             'label' => Module::t('modules/finance/app', 'Invoice order')],
            //'inv_deleted',
            // 'suppl_id',
            // 'exp_id',
            // 'invtype_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
