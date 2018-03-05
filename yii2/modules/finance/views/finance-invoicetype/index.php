<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceInvoicetypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Module::t('modules/finance/app', 'Invoice Types');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/default/infopanel'); ?>
<div class="finance-invoicetype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p class="text-right">
        <?= Html::a(Module::t('modules/finance/app', 'Create Voucher Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'invtype_title', 'label' => Module::t('modules/finance/app', 'Title')],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{delete}',
                'urlCreator' => function ($action, $model) {
                    if ($action === 'update') {
                        $url ='/finance/finance-invoicetype/update?id=' . $model->invtype_id;
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = '/finance/finance-invoicetype/delete?id=' . $model->invtype_id;
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
</div>
