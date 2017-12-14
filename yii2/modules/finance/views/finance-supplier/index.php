<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = Yii::t('app', 'Finance Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p style="text-align: right;">
        <?= Html::a(Yii::t('app', 'Create Finance Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'suppl_id',
            'suppl_name',
            'suppl_vat',
            'suppl_address',
            'suppl_phone',
            // 'suppl_fax',
            // 'suppl_iban',
            // 'suppl_employerid',
            // 'taxoffice_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
