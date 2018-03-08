<?php

use app\modules\finance\Module;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
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
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>
	
	<?php Pjax::begin();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'inv_number',
             'label' => Module::t('modules/finance/app', 'Voucher Number'),
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['attribute' => 'inv_date',
             'label' => Module::t('modules/finance/app', 'Voucher Date'),
             'format' => ['date', 'php:d-m-Y'],
             'filter' => DateControl::widget([
             'model' => $searchModel,
             'attribute' => 'inv_date',
             'widgetOptions' => [
                        'layout' => '{remove}{input}'
                    ]
                ]),
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['attribute' => 'inv_order',
             'label' => Module::t('modules/finance/app', 'Voucher Order'),
             'headerOptions' => ['class'=> 'text-center']
            ],
            //'inv_deleted',
            // 'suppl_id',
            // 'exp_id',
            // 'invtype_id',

            ['class' => 'yii\grid\ActionColumn',
             'urlCreator' => function ($action, $model, $key, $index) {
                 if ($action === 'view') {
                     $url = Url::to(['/finance/finance-invoice/view', 'id' => $model['inv_id']]);
                     //$url ='finance-invoice/view?id='.$model['inv_id'];
                     return $url;
                 }

                 if ($action === 'update') {
                     $url = Url::to(['/finance/finance-invoice/update', 'id' => $model['inv_id']]);
                     //$url ='finance-invoice/update?id='.$model['inv_id'];
                     return $url;
                 }
                 if ($action === 'delete') {
                     $url = Url::to(['/finance/finance-invoice/update', 'id' => $model['inv_id']]);
                     //$url = ['finance-invoice/delete', 'id'=> $model['inv_id']];
                     return $url;
                 }
             }
            ],
        ],
    ]); ?>
    <?php  Pjax::end();?>
</div>
