<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use app\modules\finance\models\FinanceSupplier;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
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
    
    <?=Html::beginForm(['paymentreport'], 'post');?>
		<?php $paymentreportbutton = Html::a(
    Module::t('modules/finance/app', 'Export Payment Report'),
    ['paymentreport'],
                                              ['class' => 'btn btn-success', 'data-method' => 'POST']
); ?>
		<?php $coversheetbutton = Html::a(

                                                  Module::t('modules/finance/app', 'Export Cover Sheet'),

                                                  ['coversheet'],
                                              ['class' => 'btn btn-success', 'data-method' => 'POST']

                                              ); ?>	                                          
	<?= $this->render('/default/kaeslist', [
        'kaes' => $kaes,
        'btnLiteral' => Module::t('modules/finance/app', 'Create Expenditure'),
        'actionUrl' => 'finance-expenditure/create',
        'balances' => $balances,
        'otherbuttons' => [$paymentreportbutton, $coversheetbutton]
    ]) ?> 
 
	
 		
	<?php Pjax::begin();?>
 		
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model) {
                return ['value' => $model['exp_id']];
                }],
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'suppl_id',
             'label' => Module::t('modules/finance/app', 'Supplier'),
             'format' => 'html',
             'value' => function ($model) {
                 //return "<a href='#' data-toggle='tooltip' data-placement='bottom' title='" . $model['exp_description'] . "'>" . FinanceSupplier::find()->where(['suppl_id' => $model['suppl_id']])->one()['suppl_name'] . "</a>";
                return FinanceSupplier::find()->where(['suppl_id' => $model['suppl_id']])->one()['suppl_name'];
             },
             'headerOptions' => ['class'=> 'text-center']
            ],
            ['attribute' => 'exp_amount',
             'label' => Module::t('modules/finance/app', 'Amount'),
             'format' => 'currency',
             'value' => function ($model) {
                 return Money::toCurrency($model['exp_amount']);
             },
             'contentOptions' => ['class' => 'text-nowrap text-right'],
             'headerOptions' => ['class'=> 'text-center'],
             //'contentOptions' => ['class' => 'text-right']
             //'filter' => Money::toCents($model['exp_amount']),
            ],
            ['attribute' => 'fpa_value',
             'label' => Module::t('modules/finance/app', 'VAT'),
             'format' => 'html',
             'value' => function ($model) {
                 return Money::toPercentage($model['fpa_value']);
             },
             'filter' => FinanceFpa::getFpaLevels(),
             'headerOptions' => ['class'=> 'text-center'],
             'contentOptions' => ['class' => 'text-right']
            ],
            ['attribute' => 'exp_flattaxes',
                'label' => Module::t('modules/finance/app', 'Flat taxes'),
                'format' => 'html',
                'value' => function ($model) {
                                $ret_value = '';
                                if(isset($model['exp_flattaxes'])) {
                                    $flattaxes = json_decode($model['exp_flattaxes']);                                
                                    foreach ($flattaxes as $flattax)
                                        $ret_value .= Money::toCurrency($flattax, true) . "<br />";
                                }
                                return $ret_value;
                            },
                'filter' => FinanceFpa::getFpaLevels(),
                'headerOptions' => ['class'=> 'text-center'],
                'contentOptions' => ['class' => 'text-right']
                ],
/*             ['attribute' => 'exp_date',
             'format' => ['date', 'php:d-m-Y'],
             'label' => Module::t('modules/finance/app', 'Created'),
             'filter' => DateControl::widget([
                    'model' => $searchModel,
                    'attribute' => 'exp_date',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'layout' => '{remove}{input}'
                    ],
                ]),
              'headerOptions' => ['class'=> 'text-center'],
              'contentOptions' => ['class' => 'text-center']
            ], */
            ['attribute' => 'exp_description',
                'label' => Module::t('modules/finance/app', 'Description'),
                'format' => 'html',
                'value' => function ($model) {
                    return $model['exp_description'];
                }
            ], 
            ['attribute' => 'Withdrawals', 'label' => Module::t('modules/finance/app', 'Assigned Withdrawals'),
             'format' => 'html',
                'value' => function ($model) use ($expendwithdrawals) {
                    $exp_withdrawals = $expendwithdrawals[$model['exp_id']]['WITHDRAWAL'];
                    $count_withdrawals = count($exp_withdrawals);
                    $retvalue = "";
                    //$retvalue = "<ul>";
                    for ($i = 0; $i < $count_withdrawals; $i++) {
                        $retvalue .= "<strong style='white-space:nowrap;'>- " . $exp_withdrawals[$i]['kaewithdr_decision'] . '</strong>' .
                    '<br />' . Module::t('modules/finance/app', 'Assigned Amount') . ':<br/>' .
                    Money::toCurrency($expendwithdrawals[$model['exp_id']]['EXPENDWITHDRAWAL'][$i], true);
                        $retvalue .= "<br />";
                        //$retvalue .= "</li>";
                    }
                    //$retvalue .= "</ul>";
                    return $retvalue;
                },
             'headerOptions' => ['class'=> 'text-center']
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
             'value' => function ($model) {
                 $state_commnents = [];
                 $tmp1 = FinanceExpenditurestate::findOne(['exp_id' => $model['exp_id'], 'state_id' => 1]);
                 $tmp2 = FinanceExpenditurestate::findOne(['exp_id' => $model['exp_id'], 'state_id' => 2]);
                 $tmp3 = FinanceExpenditurestate::findOne(['exp_id' => $model['exp_id'], 'state_id' => 3]);
                 $tmp4 = FinanceExpenditurestate::findOne(['exp_id' => $model['exp_id'], 'state_id' => 4]);
                 $state_commnents[1] = Module::t('modules/finance/app', "Date"). ": " . date('d/m/Y', strtotime($tmp1['expstate_date'])) .
                                                  " (" . $tmp1['expstate_comment'] . ")";
                 $state_commnents[2] = Module::t('modules/finance/app', "Date"). ": " . date('d/m/Y', strtotime($tmp2['expstate_date'])) .
                                                  " (" . $tmp2['expstate_protocol'] . " - " . $tmp2['expstate_comment'] . ")";
                 $state_commnents[3] = Module::t('modules/finance/app', "Date"). ": " . date('d/m/Y', strtotime($tmp3['expstate_date'])) .
                                                  " (" . $tmp3['expstate_comment'] . ")";
                 $state_commnents[4] = Module::t('modules/finance/app', "Date"). ": " . date('d/m/Y', strtotime($tmp4['expstate_date'])) .
                                                  " (" . $tmp4['expstate_comment'] . ")";
                 $retvalue = 'UNDEFINED STATE';
                 if ($model['statescount'] == 1) {
                     $url = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 1, 'exp_id' =>$model['exp_id']]);
                     $retvalue = Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span>',
                                         $url,
                                         ['title' => Module::t('modules/finance/app', 'Forward to next state')]
                                        );                     
                     //$retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>';
                 } elseif ($model['statescount'] == 2) {
                     $url1 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 1, 'exp_id' =>$model['exp_id']]);
                     $url2 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 2, 'exp_id' =>$model['exp_id']]);
                     $retvalue = Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span>',
                                         $url1
                                        );
                     $retvalue .= '&nbsp;';
                     $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span>',
                                          $url2
                                         );
                     //$retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>
                     //                       <a href="/finance/finance-expenditure/updatestate?state_id=2&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span></a>';
                 } elseif ($model['statescount'] == 3) {
                     $url1 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 1, 'exp_id' =>$model['exp_id']]);
                     $url2 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 2, 'exp_id' =>$model['exp_id']]);
                     $url3 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 3, 'exp_id' =>$model['exp_id']]);
                     $retvalue = Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span>', $url1);
                     $retvalue .= '&nbsp;';
                     $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span>', $url2);
                     $retvalue .= '&nbsp;';
                     $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:orange;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[3] . '"></span>', $url3);
//                     $retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>
//                                            <a href="/finance/finance-expenditure/updatestate?state_id=2&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span></a>
//                                            <a href="/finance/finance-expenditure/updatestate?state_id=3&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:orange;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[3] . '"></span></a>';
                 } elseif ($model['statescount'] == 4) {
                     $url1 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 1, 'exp_id' =>$model['exp_id']]);
                     $url2 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 2, 'exp_id' =>$model['exp_id']]);
                     $url3 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 3, 'exp_id' =>$model['exp_id']]);
                     $url4 = Url::to(['/finance/finance-expenditure/updatestate', 'state_id' => 4, 'exp_id' =>$model['exp_id']]);
                     $retvalue = Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span>', $url1);
                     $retvalue .= '&nbsp;';
                     $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span>', $url2);
                     $retvalue .= '&nbsp;';
                     $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:orange;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[3] . '"></span>', $url3);
                     $retvalue .= '&nbsp;';
                     $retvalue .= Html::a('<span class="glyphicon glyphicon-ok-sign" style="color:green;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[4] . '"></span>', $url4);                     
//                     $retvalue = '<a href="/finance/finance-expenditure/updatestate?state_id=1&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:blue;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[1] . '"></span></a>
//                                            <a href="/finance/finance-expenditure/updatestate?state_id=2&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:red; data-toggle="tooltip" data-html="true" title="' . $state_commnents[2] . '"></span></a>
//                                            <a href="/finance/finance-expenditure/updatestate?state_id=3&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:orange;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[3] . '"></span></a>
//                                            <a href="/finance/finance-expenditure/updatestate?state_id=4&exp_id=' . $model['exp_id'] . '"><span class="glyphicon glyphicon-ok-sign" style="color:green;" data-toggle="tooltip" data-html="true" title="' . $state_commnents[4] . '"></span></a>';
                 }
                 return $retvalue;
             },
               'headerOptions' => ['class'=> 'text-center'],
               'contentOptions' => ['class' => 'text-center']
            ],
            [   'attribute' => 'invoice',
                'header' => '<span class="text-wrap">' . Module::t('modules/finance/app', 'Voucher<br />Actions') . '</span>',
                'format' => 'html',
                'value' => function ($model) use ($expendwithdrawals) {
                    $retvalue = "";
                    if (is_null($expendwithdrawals[$model['exp_id']]['INVOICE'])) {
                        $url = Url::to(['/finance/finance-invoice/create', 'expenditures_return' => 1, 'id' =>$model['exp_id']]);
                        $retvalue = Html::a('<span class="glyphicon glyphicon-list-alt"></span>', 
                                            $url,
                                            //'finance-invoice/create?expenditures_return=1&id=' . $model['exp_id'],
                                            ['title' => Module::t(
                                                'modules/finance/app',
                                                'Create invoice for the expenditure.'
                                            )]
                                            );
                    } 
                    else {
                        $url1 = Url::to(['/finance/finance-invoice/view', 'expenditures_return' => 1, 'id' => $expendwithdrawals[$model['exp_id']]['INVOICE']]);
                        $retvalue = Html::a(
                                '<span class="glyphicon glyphicon-eye-open"></span>',
                                $url1,
                                //'finance-invoice/view?expenditures_return=1&id=' . $expendwithdrawals[$model['exp_id']]['INVOICE'],
                                ['title' => Module::t(
                                    'modules/finance/app',
                                    'View the invoice details for the expenditure.'
                                )]
                            );
                        $url2 = Url::to(['/finance/finance-invoice/update', 'expenditures_return' => 1, 'id' => $expendwithdrawals[$model['exp_id']]['INVOICE']]);
                        $retvalue .= "&nbsp;" . Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    $url2,
                                    //'finance-invoice/update?expenditures_return=1&id=' . $expendwithdrawals[$model['exp_id']]['INVOICE'],
                                    ['title' => Module::t(
                                        'modules/finance/app',
                                        'Update the invoice details for the expenditure.'
                                    )]
                                );
                    }
                    $retvalue .= "";
                    return $retvalue;
                },
                'headerOptions' => ['class'=> 'text-center'],
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
            ['class' => 'yii\grid\ActionColumn',
             'header' => Module::t('modules/finance/app', 'Expenditure<br />Actions'),
             'contentOptions' => ['class' => 'text-nowrap'],
             'template' => '{backwardstate} {forwardstate} {update} {delete}',
                'buttons' => [
                    'forwardstate' => function ($url, $model) {
                        if ($model['statescount'] != 4) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-arrow-right"></span>',
                                $url,
                                           ['title' => Module::t('modules/finance/app', 'Forward to next state')]
                            );
                        }
                    },
                        'backwardstate' => function ($url, $model) {
                            if ($model['statescount'] > 1) {
                                return Html::a(
                                '<span class="glyphicon glyphicon-arrow-left"></span>',
                                $url,
                                ['title' => Module::t('modules/finance/app', 'Backward to previous state'),
                                 'data'=>['confirm'=>Module::t('modules/finance/app', "Are you sure you want to change the state of the expenditure?"),
                                 'method' => "post"]
                                ]
                            );
                            }
                        }
                    ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'delete') {
                        $url = Url::to(['/finance/finance-expenditure/delete', 'id' =>$model['exp_id']]);
                        //$url = 'finance-expenditure/delete?id=' . $model['exp_id'];
                        return $url;
                    }
                    if ($action === 'update') {
                        $url = Url::to(['/finance/finance-expenditure/update', 'id' =>$model['exp_id']]);
                        //$url = 'finance-expenditure/update?id=' . $model['exp_id'];
                        return $url;
                    }
                    if ($action === 'backwardstate') {
                        $url = Url::to(['/finance/finance-expenditure/backwardstate', 'id' =>$model['exp_id']]);
                        //$url ='finance-expenditure/backwardstate?id=' . $model['exp_id'];
                        return $url;
                    }
                    if ($action === 'forwardstate') {
                        $url = Url::to(['/finance/finance-expenditure/forwardstate', 'id' =>$model['exp_id']]);
                        //$url ='finance-expenditure/forwardstate?id=' . $model['exp_id'];
                        return $url;
                    }
                },
              'headerOptions' => ['class'=> 'text-center']
            ],
       ],
    ]); ?>
    <?php  Pjax::end();?>
    <!--<p style="text-align: right;">
    	<?= Html::submitButton(
        Module::t('modules/finance/app', 'Export Payment Report'),
                                                           ['class' => 'btn btn-success']
    );?>
	</p>-->	                                                               
    <?= Html::endForm();?>
</div>