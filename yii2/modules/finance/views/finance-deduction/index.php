<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceDeductionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Module::t('modules/finance/app', 'Deductions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-deduction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a(Module::t('modules/finance/app', 'Create Deduction'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'deduct_name', 'label' => Module::t('modules/finance/app', 'Title')],
            ['attribute' => 'deduct_description', 'label' => Module::t('modules/finance/app', 'Description')],
            ['attribute' => 'deduct_percentage',
                'label' => Module::t('modules/finance/app', 'Percentage'),
                'format' => 'html',
                'value' => function ($model) {return Money::toPercentage($model['deduct_percentage']);}
            ],
            ['attribute' => 'deduct_downlimit',
                'label' => Module::t('modules/finance/app', 'Minimum amount'),
                'format' => 'html',
                'value' => function ($model) {return Money::toCurrency($model['deduct_downlimit'], true);}
            ],
            ['attribute' => 'deduct_uplimit',
                'label' => Module::t('modules/finance/app', 'Maximum amount'),
                'format' => 'html',
                'value' => function ($model) {return Money::toCurrency($model['deduct_uplimit'], true);}
            ],            
            ['attribute' => 'deduct_date', 'label' => Module::t('modules/finance/app', 'Created')],
            ['attribute' => 'deduct_obsolete',
                'label' => Module::t('modules/finance/app', 'Obsolete'),
                'format' => 'html',
                'value' => function ($model) {
                               if($model['deduct_obsolete'] == 0)
                                    return Module::t('modules/finance/app', 'Όχι');
                               else
                                   return Module::t('modules/finance/app', 'Ναι');
                            }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{delete}',
                    'urlCreator' => function ($action, $model) {
                    if ($action === 'update') {
                        $url ='/finance/finance-deduction/update?id=' . $model->deduct_id;
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = '/finance/finance-deduction/delete?id=' . $model->deduct_id;
                        return $url;
                    }
                },
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ],
    ]); ?>
</div>
