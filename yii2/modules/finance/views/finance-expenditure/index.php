<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use app\modules\finance\models\FinanceSupplier;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\finance\models\FinanceExpenditurestate;
use kartik\datecontrol\DateControl;
use app\modules\finance\models\FinanceFpa;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceExpenditureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = Module::t('modules/finance/app', 'Expenditures');
$this->params['breadcrumbs'][] = $this->title;

//echo "<pre>"; print_r(FinanceFpa::getFpaLevels()); echo "</pre>"; die();
?>
<?= $this->render('/default/infopanel');?>
<div class="finance-expenditure-index">
    <h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('/default/kaeslist', [
        'kaes' => $kaes,
        'btnLiteral' => Module::t('modules/finance/app', 'Create Expenditure'),
        'actionUrl' => '/finance/finance-expenditure/create',
	    'balances' => $balances,	    
    ]) ?> 
 
	<?=Html::beginForm(['paymentreport'],'post');?>
 		
	<?php Pjax::begin();?>
 		
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
             'format' => 'currency',
             'value' => function ($model) {return Money::toCurrency($model['exp_amount']);},
             'contentOptions' => ['class' => 'text-nowrap'],
             //'filter' => Money::toCents($model['exp_amount']),
            ],
            ['attribute' => 'fpa_value', 
             'label' => Module::t('modules/finance/app', 'VAT'),
             'format' => 'html',
             'value' => function ($model) {return Money::toPercentage($model['fpa_value']);},
             'filter' => FinanceFpa::getFpaLevels(),
            ],
            ['attribute' => 'exp_date',
             'format' => ['date', 'php:d-m-Y'],
             'label' => Module::t('modules/finance/app', 'Created'),
                'filter' => DateControl::widget([
                    'model' => $searchModel,
                    'attribute' => 'exp_date',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'layout' => '{remove}{input}'
                    ],
                ])
            ],
            ['attribute' => 'exp_description',
                'label' => Module::t('modules/finance/app', 'Description'),
                'format' => 'html',
                'value' => function ($model) {return $model['exp_description'];}
            ],
            ['attribute' => 'Withdrawals', 'label' => Module::t('modules/finance/app', 'Assigned Withdrawals'),
             'format' => 'html',
                'value' => function($model) use ($expendwithdrawals) {
                $exp_withdrawals = $expendwithdrawals[$model['exp_id']]['WITHDRAWAL'];
                $count_withdrawals = count($exp_withdrawals);
                $retvalue = "<ul>";
                for($i = 0; $i < $count_withdrawals; $i++){
                    $retvalue .= "<li><strong><u>" . $exp_withdrawals[$i]['kaewithdr_decision'] . '</u></strong>' . 
                    '<br />' . Module::t('modules/finance/app', 'Assigned Amount') . ': ' .
                    Money::toCurrency($expendwithdrawals[$model['exp_id']]['EXPENDWITHDRAWAL'][$i], true);
                    $retvalue .= "</li>";
                }
                $retvalue .= "</ul>";
                return $retvalue;
             }
            ],
            ['attribute' => 'kae_id',
                'label' => Module::t('modules/finance/app', 'RCN'),
                'format' => 'html',
                'value' => function ($model) {
                                //return $expendwithdrawals[$model['exp_id']]['RELATEDKAE'];
                                return sprintf('%04d', $model['kae_id']);
                            }
            ],
            ['attribute' => 'statescount', 
             'label' => Module::t('modules/finance/app', 'State'),
             'format' => 'html',
             'contentOptions' => ['class' => 'text-nowrap'],
             'value' => function($model) {
                            $state_commnents = array();
                            $tmp = FinanceExpenditurestate::findOne(['exp_id' => $model['exp_id'], 'state_id' => 1]);
                            $state_commnents[1] = Module::t('modules/finance/app', "Date"). ": " . $tmp['expstate_date'] .  
                                                  " (" . $tmp['expstate_comment'] . ")";
                            $state_commnents[2] = Module::t('modules/finance/app', "Date"). ": " . $tmp['expstate_date'] .
                                                  " (" . $tmp['expstate_comment'] . ")";
                            $state_commnents[3] = Module::t('modules/finance/app', "Date"). ": " . $tmp['expstate_date'] .
                                                  " (" . $tmp['expstate_comment'] . ")";
                            $state_commnents[4] = Module::t('modules/finance/app', "Date"). ": " . $tmp['expstate_date'] .
                                                  " (" . $tmp['expstate_comment'] . ")";
                            $retvalue = 'UNDEFINED STATE';
                            if($model['statescount'] == 1)
                                $retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>';
                            else if($model['statescount'] == 2)
                                $retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>
                                            &nbsp;<a href="/finance/finance-expenditure/updatestate?state_id=2&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span></a>';
                            else if($model['statescount'] == 3)
                                $retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>
                                            &nbsp;<a href="/finance/finance-expenditure/updatestate?state_id=2&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span></a>
                                            &nbsp;<a href="/finance/finance-expenditure/updatestate?state_id=3&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:orange;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[3] . '"></span></a>';
                            else if($model['statescount'] == 4)
                                $retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>
                                            &nbsp;<a href="/finance/finance-expenditure/updatestate?state_id=2&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span></a>
                                            &nbsp;<a href="/finance/finance-expenditure/updatestate?state_id=3&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:orange;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[3] . '"></span></a>
                                            &nbsp;<a href="/finance/finance-expenditure/updatestate?state_id=4&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:green;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[4] . '"></span></a>';                            
                            return $retvalue;                            
                        }
            ],
            [   'attribute' => 'invoice',
                'header' => '<span class="text-wrap">' . Module::t('modules/finance/app', 'Voucher<br />Actions') . '</span>',
                'format' => 'html',
                'value' => function ($model) use ($expendwithdrawals){
                $retvalue = "";
                if(is_null($expendwithdrawals[$model['exp_id']]['INVOICE']))
                    $retvalue = Html::a('<span class="glyphicon glyphicon-list-alt"></span>',
                        '/finance/finance-invoice/create?expenditures_return=1&id=' . $model['exp_id'],
                        ['title' => Module::t('modules/finance/app',
                            'Create invoice for the expenditure.')]);
                        else {
                            $retvalue = Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                '/finance/finance-invoice/view?expenditures_return=1&id=' . $expendwithdrawals[$model['exp_id']]['INVOICE'],
                                ['title' => Module::t('modules/finance/app',
                                    'View the invoice details for the expenditure.')]);
                                $retvalue .= "&nbsp;" . Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                    '/finance/finance-invoice/update?expenditures_return=1&id=' . $expendwithdrawals[$model['exp_id']]['INVOICE'],
                                    ['title' => Module::t('modules/finance/app',
                                        'Update the invoice details for the expenditure.')]);
                        }
                        $retvalue .= "";
                        return $retvalue;
                        
                },
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
            ['class' => 'yii\grid\ActionColumn',
             'header' => Module::t('modules/finance/app', 'Expenditure<br />Actions'),
             'contentOptions' => ['class' => 'text-nowrap'],
             'template' => '{backwardstate} {forwardstate} {update} {delete}',
                'buttons' => [
                    'forwardstate' => function ($url, $model) {
                        if($model['statescount'] != 4){
                            return Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', $url,
                                           ['title' => Module::t('modules/finance/app', 'Forward to next state')]);
                            }
                        },
                        'backwardstate' => function ($url, $model) {
                        if($model['statescount'] > 1){
                            return Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', $url,
                                ['title' => Module::t('modules/finance/app', 'Backward to previous state'),
                                 'data'=>['confirm'=>Module::t('modules/finance/app', "Are you sure you want to change the state of the expenditure?"),
                                 'method' => "post"]
                                ]);
                            }
                        }
                    ],                    
                'urlCreator' => function ($action, $model) {
                    if ($action === 'delete') {
                        $url = '/finance/finance-expenditure/delete?id=' . $model['exp_id'];
                        return $url;
                    }
                    if ($action === 'update') {
                        $url = '/finance/finance-expenditure/update?id=' . $model['exp_id'];
                        return $url;
                    }
                    if ($action === 'backwardstate') {
                        $url ='/finance/finance-expenditure/backwardstate?id=' . $model['exp_id'];
                        return $url;
                    }
                    if ($action === 'forwardstate') {
                        $url ='/finance/finance-expenditure/forwardstate?id=' . $model['exp_id'];
                        return $url;
                    }

                }
            ],
            ['class' => 'yii\grid\CheckboxColumn', 
                        'checkboxOptions' => function($model){return ['value' => $model['exp_id']];}],
       ],
    ]); ?>
    <?php  Pjax::end();?>
    <p style="text-align: right;">
    	<?= Html::submitButton(Module::t('modules/finance/app', 'Export Payment Report'), 
	                                                       ['class' => 'btn btn-success',]);?>
	</p>	                                                               
    <?= Html::endForm();?>
</div>