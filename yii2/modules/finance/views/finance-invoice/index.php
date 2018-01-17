<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Finance Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Finance Invoice'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'inv_id',
            'inv_number',
            'inv_date',
            'inv_order',
            'inv_deleted',
            // 'suppl_id',
            // 'exp_id',
            // 'invtype_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
