<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaecreditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Module::t('modules/finance/app', 'RCN Credits');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = $this->title;

$provider = new ArrayDataProvider([
    'allModels' => $dataProvider,
    'pagination' => false,
    'sort' => ['attributes' => ['kae_id', 'kae_title', 'kaecredit_amount', 'kaecredit_date', 'kaecredit_updated'],
               'defaultOrder' => ['kae_id' => SORT_ASC]],
]);
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kaecredit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p class="pull-right">
    	<?= Html::a(Module::t('modules/finance/app', 'Update Year Credit'), ['/finance/finance-year/update?id=' . Yii::$app->session["working_year"]], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Module::t('modules/finance/app', 'Set RCN Credits'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([  'dataProvider' => $provider,
                               'columns' => [
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
                                   /*['attribute' => 'kaecredit_date', 'label' => Module::t('modules/finance/app', 'Created'),
                                    'format' => ['date', 'php:d-m-Y (H:i:s)'],
                                   ],
                                   ['attribute' => 'kaecredit_updated', 'label' => Module::t('modules/finance/app', 'Τροποποιήθηκε'),
                                    'format' => ['date', 'php:d-m-Y (H:i:s)'],
                                   ],*/
                                   ['attribute' => 'kaecredit_amount',
                                    'label' => Module::t('modules/finance/app', 'Credit Amount'),
                                    'value' => function ($model) {
                                        return Money::toCurrency($model['kaecredit_amount'], true);
                                    },
                                    'headerOptions' => ['class'=> 'text-center'],
                                    'contentOptions' => ['class' => 'text-right']
                                   //'contentOptions' => ['class' => 'text-right']
                                   ],

                                ]
                            ]);
    ?>
</div>

