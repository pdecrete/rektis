<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;

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
    'sort' => ['attributes' => ['kae_id', 'kae_title', 'kaecredit_amount', 'kaecredit_date', 'kaecredit_updated']],
]);
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kaecredit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a(Module::t('modules/finance/app', 'Set RCN Credits'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget(   [  'dataProvider' => $provider,
	                           'columns' => [  
                                                'kae_id',
                                                'kae_title',
                                                'kaecredit_date',
	                                            'kaecredit_updated',
                                                ['attribute' => 'kaecredit_amount',
            	                                 'format' => 'html',
            	                                 'value' => function ($model) {return Money::toCurrency($model['kaecredit_amount']);}
                                                ],
                                            ]
	                        ]);
    ?>
</div>

