<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\finance\Module;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaecreditpercentageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('modules/finance/app', 'RCN Credits Percentages');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kaecreditpercentage-index">

    <h1><?= Html::encode($this->title) ?></h1>
		<?php $otherbutton = Html::a(
    Module::t('modules/finance/app', 'Automatic Percentage Definition'),
    ['masspercentage'],
                                           ['class' => 'btn btn-success', 'data-method' => 'POST',
                                            'data-confirm' => Module::t(

                                                'modules/finance/app',
                                            'Are you sure you want to automatically set credit percentages for specific RCNs?'

                                            )]
); ?>
    <?= $this->render('/default/kaeslist', [
        'kaes' => $kaes,
        'btnLiteral' => Module::t('modules/finance/app', 'Attribute New Percentage'),
        'actionUrl' => 'finance-kaecreditpercentage/create',
        'otherbuttons' => [$otherbutton],
        //'otherbuttons' => [[Module::t('modules/finance/app', 'Automatic Percentage Definition'),'masspercentage']]
    ]) ?>
	
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
                return (strlen($model['kae_id']) <= 4) ? sprintf('%04d', $model['kae_id']) : $model['kae_id'];
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-center']
            ],
            ['attribute' => 'kae_title', 'label' => Module::t('modules/finance/app', 'RCN Title'),
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['attribute' => 'kaecredit_amount',
             'label' => Module::t('modules/finance/app', 'Credit Amount'),
             'format' => 'currency',
             'value' => function ($model) {
                 return Money::toCurrency($model['kaecredit_amount']);
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'kaeperc_percentage',
             'label' => Module::t('modules/finance/app', 'Percentage'),
             'format' => 'html',
             'value' => function ($model) {
                 return Money::toPercentage($model['kaeperc_percentage']);
             },
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'kaeperc_date', 'label' => Module::t('modules/finance/app', 'Date'),
             'format' => ['date', 'php:d-m-Y (H:i:s)'],
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-center']
            ],
            ['attribute' => 'sumpercentage',
                'label' => Module::t('modules/finance/app', 'Total Percentage'),
                'format' => 'html',
                'value' => function ($model) {
                    return Money::toPercentage($model['sumpercentage']);
                },
                'headerOptions' => ['class'=> 'text-center'],
                'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'kaeperc_decision', 'label' => Module::t('modules/finance/app', 'Decision'),
             'headerOptions' => ['class'=> 'text-center']
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
                                                        'data'=>['confirm'=>Module::t('modules/finance/app', "The deletion of the percentage attribution of a RCN credit is irreversible action. Are you sure you want to delete this item?"),
                                                        'method' => "post"]]
                                                );
                                },
                            ],
                            'urlCreator' => function ($action, $model) {
                                if ($action === 'update') {
                                    $url = Url::to(['/finance/finance-kaecreditpercentage/update', 'id' =>$model['kaeperc_id']]);
                                    //$url ='finance-kaecreditpercentage/update?id=' . $model['kaeperc_id'];
                                    return $url;
                                }
                                if ($action === 'delete') {
                                    $url = Url::to(['/finance/finance-kaecreditpercentage/delete', 'id' =>$model['kaeperc_id']]);
                                    //$url = 'finance-kaecreditpercentage/delete?id=' . $model['kaeperc_id'];
                                    return $url;
                                }
                            },
                            'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ],
    ]); ?>
    
</div>
