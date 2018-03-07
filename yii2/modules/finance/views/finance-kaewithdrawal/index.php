<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaewithdrawalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('modules/finance/app', 'Withdrawals from RCN Credits');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kaewithdrawal-index">

    <h1><?= Html::encode($this->title) ?></h1>
   
	<?=
        $this->render('/default/kaeslist', [
        'kaes' => $kaes,
        //'balances' => $balances,
        'btnLiteral' => Module::t('modules/finance/app', 'New Withdrawal'),
        'actionUrl' => 'finance-kaewithdrawal/create'])
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-center']
            ],
            ['attribute' => 'kae_id',
             'label' => Module::t('modules/finance/app', 'RCN'),
             'format' => 'html',
             'value' => function ($model) {
                 return sprintf('%04d', $model['kae_id']);
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-center']
            ],
            ['attribute' => 'kae_title', 'label' => Module::t('modules/finance/app', 'RCN Title'),
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['attribute' => 'kaecredit_amount',
             'format' => 'currency',
             'label' => Module::t('modules/finance/app', 'Credit Amount'),
             'value' => function ($dataProvider) {
                 return Money::toCurrency($dataProvider['kaecredit_amount']);
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'percentages',
             'label' => Module::t('modules/finance/app', 'Spending Rate'),
             'value' => function ($dataProvider) {
                 return Money::toPercentage($dataProvider['percentages']);
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'kaewithdr_amount',
             'format' => 'currency',
             'label' => Module::t('modules/finance/app', 'Withdrawal Amount'),
             'value' => function ($dataProvider) {
                 return Money::toCurrency($dataProvider['kaewithdr_amount']);
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'kaewithdr_decision', 'label' => Module::t('modules/finance/app', 'Withdrawal Decision')],
            ['attribute' => 'kaewithdr_date', 'label' => Module::t('modules/finance/app', 'Withdrawal Date'),
             'format' => ['date', 'php:d-m-Y (H:i:s)'],
             'headerOptions' => ['class'=> 'text-center'],
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;{delete}',
                'buttons' =>   [   'update' => function ($url, $model) {
                    return Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>',
                    $url,
                    ['title' => Module::t('modules/finance/app', 'Update')]
                );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    $url,
                    ['title' => Module::t('modules/finance/app', 'Delete'),
                        'data'=>['confirm'=>Module::t('modules/finance/app', "The deletion of the withdrawal is irreversible action. Are you sure you want to delete this item?"),
                            'method' => "post"]]
                );
                },
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'update') {
                        $url = Url::to(['/finance/finance-kaewithdrawal/update', 'id' =>$model['kaewithdr_id']]);
                        //$url ='finance-kaewithdrawal/update?id=' . $model['kaewithdr_id'];
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(['/finance/finance-kaewithdrawal/delete', 'id' =>$model['kaewithdr_id']]);
                        //$url = 'finance-kaewithdrawal/delete?id=' . $model['kaewithdr_id'];
                        return $url;
                    }
                },
                'contentOptions' => ['class' => 'text-nowrap'],
           ],
        ],
    ]); ?>
</div>