<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceDeductionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Yii::t('app', 'Finance Deductions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-deduction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Finance Deduction'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'deduct_id',
            'deduct_name',
            'deduct_description',
            'deduct_date',
            'deduct_percentage',
            'deduct_downlimit',
            'deduct_uplimit',
            'deduct_obsolete',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
