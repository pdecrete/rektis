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
<?= $this->render('/default/infopanel'); ?>
<div class="finance-deduction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p class="text-right">
        <?= Html::a(Module::t('modules/finance/app', 'Create Deduction'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
             'headerOptions' => ['class'=> 'text-center'], 'contentOptions' => ['class' => 'text-center']],
            ['attribute' => 'deduct_name', 'label' => Module::t('modules/finance/app', 'Title'),
             'headerOptions' => ['class'=> 'text-center']],
            ['attribute' => 'deduct_description', 'label' => Module::t('modules/finance/app', 'Description'),
             'headerOptions' => ['class'=> 'text-center']],
            ['attribute' => 'deduct_percentage',
             'label' => Module::t('modules/finance/app', 'Percentage'),
             'format' => 'html',
             'value' => function ($model) {
                 return Money::toPercentage($model['deduct_percentage']);
             },
             'headerOptions' => ['class'=> 'text-center'], 'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'deduct_downlimit',
             'label' => Module::t('modules/finance/app', 'Minimum amount'),
             'format' => 'html',
             'value' => function ($model) {
                 return Money::toCurrency($model['deduct_downlimit'], true);
             },
             'headerOptions' => ['class'=> 'text-center'], 'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'deduct_uplimit',
             'label' => Module::t('modules/finance/app', 'Maximum amount'),
             'format' => 'html',
             'value' => function ($model) {
                 return Money::toCurrency($model['deduct_uplimit'], true);
             },
             'headerOptions' => ['class'=> 'text-center'], 'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'deduct_date', 'label' => Module::t('modules/finance/app', 'Created'),
             'headerOptions' => ['class'=> 'text-center'], 'contentOptions' => ['class' => 'text-center']
            ],
            ['attribute' => 'deduct_obsolete',
             'label' => Module::t('modules/finance/app', 'Obsolete'),
             'format' => 'html',
             'value' => function ($model) {
                 if ($model['deduct_obsolete'] == 0) {
                     return Module::t('modules/finance/app', 'Όχι');
                 } else {
                     return '<strong>' . Module::t('modules/finance/app', 'Ναι') . '</strong>';
                 }
             },
             'headerOptions' => ['class'=> 'text-center'], 'contentOptions' => ['class' => 'text-center']
            ],
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{update}&nbsp;{delete}&nbsp;{activate}',
             'buttons' => [
                    'activate' => function ($url, $model) {
                        return Html::a(
                                        '<span class=" 	glyphicon glyphicon-ok-circle"></span>',
                                        $url,
                                                    ['title' => Module::t('modules/finance/app', 'Ενεργοποίηση'),
                                                     'data'=>['confirm'=>Module::t('modules/finance/app', "Are you sure you want to reactivate the deduction?"),
                                                              'method' => "post"]
                                                    ]
                                    );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                                      '<span class=" 	glyphicon glyphicon-ban-circle"></span>',
                                      $url,
                                      ['title' => Module::t('modules/finance/app', 'Κατάργηση'),
                                       'data'=>['confirm'=>Module::t('modules/finance/app', "Are you sure you want to make the deduction obselete?"),
                                                'method' => "post"]
                                      ]
                                  );
                    }
                ],
             'urlCreator' => function ($action, $model) {
                 if ($action === 'update') {
                     $url ='finance-deduction/update?id=' . $model->deduct_id;
                     return $url;
                 }
                 if ($action === 'delete') {
                     $url = 'finance-deduction/delete?id=' . $model->deduct_id;
                     return $url;
                 }
                 if ($action === 'activate') {
                     $url = 'finance-deduction/activate?id=' . $model->deduct_id;
                     return $url;
                 }
             },
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ],
    ]); ?>
</div>
