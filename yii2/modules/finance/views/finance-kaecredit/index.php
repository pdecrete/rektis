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
	                               ['attribute' => 'kae_id', 
	                                'label' => Module::t('modules/finance/app', 'RCN'),
	                                'format' => 'html',
	                                'value' => function ($model) {return sprintf('%04d', $model['kae_id']);}                                   
	                               ],
	                               ['attribute' => 'kae_title', 'label' => Module::t('modules/finance/app', 'RCN Title')],
	                               ['attribute' => 'kaecredit_date', 'label' => Module::t('modules/finance/app', 'Created')],
	                               ['attribute' => 'kaecredit_updated', 'label' => Module::t('modules/finance/app', 'Τροποποιήθηκε')],	                                            
                                   ['attribute' => 'kaecredit_amount',
                                    'label' => Module::t('modules/finance/app', 'Credit Amount'),
                                    'format' => 'currency',
                                    //'contentOptions' => ['class' => 'text-right']
                                   ],
                                 
                                ]
	                        ]);
    ?>
</div>

