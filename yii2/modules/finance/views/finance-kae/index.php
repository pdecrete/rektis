<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->title = Module::t('modules/finance/app', 'RCN', ['plural' => 1]);
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kae-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p style="text-align: right;">
        <?= Html::a(Module::t('modules/finance/app', 'Create new RCN'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-center']
            ],
            ['attribute' => 'kae_id',
             'format' => 'html',
             'value' => function ($model) {
                return (strlen($model['kae_id']) <= 4) ? sprintf('%04d', $model['kae_id']) : $model['kae_id'];
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-center']
            ],
            ['attribute' => 'kae_title',
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['attribute' => 'kae_description',
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{update}'
            ],
        ],
    ]); ?>
</div>
