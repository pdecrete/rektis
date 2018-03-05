<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceFpaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Module::t('modules/finance/app', 'VAT Options');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-fpa-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-right">
        <?= Html::a(Module::t('modules/finance/app', 'Create VAT option'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'fpa_value', 'label' => Module::t('modules/finance/app', 'VAT Percentage')],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{update}&nbsp;{delete}',
             'urlCreator' => function ($action, $model) {
                 if ($action === 'update') {
                     $url ='/finance/finance-fpa/update?id=' . Money::toDbPercentage($model['fpa_value']);
                     return $url;
                 }
                 if ($action === 'delete') {
                     $url = '/finance/finance-fpa/delete?id=' . Money::toDbPercentage($model['fpa_value']);
                     return $url;
                 }
             }
            ],
        ],
    ]); ?>
</div>