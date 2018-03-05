<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Module::t('modules/finance/app', 'Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p style="text-align: right;">
        <?= Html::a(Module::t('modules/finance/app', 'Create Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'suppl_name', 'label' => Module::t('modules/finance/app', 'Επωνυμία'),
             'headerOptions' => ['class'=> 'text-center']],
            ['attribute' => 'suppl_vat', 'label' => Module::t('modules/finance/app', 'ΑΦΜ'),
             'headerOptions' => ['class'=> 'text-center']],
            ['attribute' => 'suppl_address', 'label' => Module::t('modules/finance/app', 'Διεύθυνση'),
             'headerOptions' => ['class'=> 'text-center']],
            ['attribute' => 'suppl_phone', 'label' => Module::t('modules/finance/app', 'Τηλέφωνο'),
             'headerOptions' => ['class'=> 'text-center']],
            ['attribute' => 'suppl_fax', 'label' => Module::t('modules/finance/app', 'Φαξ'),
             'headerOptions' => ['class'=> 'text-center']],
            ['attribute' => 'suppl_iban', 'label' => Module::t('modules/finance/app', 'IBAN'),
             'headerOptions' => ['class'=> 'text-center']],
            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['class' => 'text-nowrap'],
             'headerOptions' => ['class'=> 'text-center']],
        ],
    ]); ?>
</div>
