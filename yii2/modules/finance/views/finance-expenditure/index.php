<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\finance\models\FinanceSupplier;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceExpenditureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = Module::t('modules/finance/app', 'Finance Expenditures');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="finance-expenditure-index">
	
	<?= $this->render('/default/infopanel');?>
	
    <h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('/default/kaeslist', [
        'kaes' => $kaes,
        'btnLiteral' => Module::t('modules/finance/app', 'Create Finance Expenditure'),
        'actionUrl' => '/index.php/finance/finance-expenditure/create'
    ]) ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'suppl_id',
             'label' => Module::t('modules/finance/app', 'Supplier'),
             'format' => 'html',
             'value' => function ($model) {return FinanceSupplier::find()->where(['suppl_id' => $model['suppl_id']])->one()['suppl_name'];}
            ],
            ['attribute' => 'exp_amount', 
             'label' => Module::t('modules/finance/app', 'Amount'),
             'format' => 'html',
             'value' => function ($model) {return Money::toCurrency($model['exp_amount']);}
            ],
//            ['attribute' => 'exp_lock', 'label' => Module::t('modules/finance/app', 'Supplier')],
//            ['attribute' => 'exp_deleted', 'label' => Module::t('modules/finance/app', 'Supplier')],
            ['attribute' => 'fpa_value', 
             'label' => Module::t('modules/finance/app', 'VAT'),
             'format' => 'html',
             'value' => function ($model) {return Money::toPercentage($model['fpa_value']);}
            ],
            ['attribute' => 'exp_date', 'label' => Module::t('modules/finance/app', 'Creation Date')],
            ['attribute' => 'statescount', 
             'label' => Module::t('modules/finance/app', 'State'),
             'format' => 'html',
             'value' => function($model) {
                            $retvalue = 'UNDEFINED STATE';
                            if($model['statescount'] == 1)
                                $retvalue = '<span class="glyphicon glyphicon-ok-sign" style="color:blue;"></span>';
                            else if($model['statescount'] == 2)
                                $retvalue = '<span class="glyphicon glyphicon-ok-sign" style="color:blue;"></span>
                                          &nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:red;"></span>';
                            else if($model['statescount'] == 3)
                                $retvalue = '<span class="glyphicon glyphicon-ok-sign" style="color:blue;"></span>
                                      &nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:red;"></span>
                                      &nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:orange;"></span>';
                            else if($model['statescount'] == 4)
                                $retvalue = '<span class="glyphicon glyphicon-ok-sign" style="color:blue;"></span>
                                      &nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:red;"></span>
                                      &nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:orange;"></span>
                                      &nbsp;<span class="glyphicon glyphicon-ok-sign" style="color:green;"></span>';
                            return $retvalue;                            
                        }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>