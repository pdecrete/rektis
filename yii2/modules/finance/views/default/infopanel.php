<?php
use app\modules\finance\Module;
use app\modules\finance\models\FinanceYear;
use app\modules\finance\components\Integrity;
use app\modules\finance\components\Money;
use dosamigos\chartjs\ChartJs;
use app\modules\finance\models\FinanceKaecredit;
use app\modules\finance\models\FinanceKaecreditpercentage;
use app\modules\finance\models\FinanceKaewithdrawal;

if(Integrity::uniqueCurrentYear()):

    $credits = FinanceKaecredit::find()->where(['year' => Yii::$app->session["working_year"]])->all();
    $labels = array();
    $data = array();
    $available = array();
    $withdrawals = array();
    $withdrawalsbalance = array();

    $index = 0;
    foreach ($credits as $credit){
        if($credit->kaecredit_amount != 0){
            $kaecredit_percentage = FinanceKaecreditpercentage::getKaeCreditSumPercentage($credit->kaecredit_id);
            $labels[$index] = sprintf('%04d', $credit->kae_id);
            $data[$index] = Money::toCurrency($credit->kaecredit_amount);
            $available[$index] = Money::toCurrency($credit->kaecredit_amount*Money::dbPercentagetoDecimal($kaecredit_percentage));            
            $withdrawals[$index] = Money::toCurrency(FinanceKaewithdrawal::getWithdrawsSum($credit->kaecredit_id));
            $withdrawalsbalance[$index] = Money::toCurrency(FinanceKaewithdrawal::getWithdrawalsBalance($credit->kaecredit_id));
            $index++;
        }
    }
    $chart_height = 60;
    //if(count($data) <= 3)
        //$chart_height = 80;
    //else
    //    $chart_height = count($data)*40;            
?>
    <div class="row">
    
    	<!-- div class="col-lg-4">&nbsp;</div-->
    	<!-- div class="col-lg-4">&nbsp;</div-->
    	<div class="col-lg-12">
        	<div class="panel panel-default">
            	<div class="panel-heading">
                  	<span class="label label-info">
                  		<?= Module::t('modules/finance/app', 'Currently Working Year');?><strong>: <?= Yii::$app->session["working_year"] ?></strong>
                  	</span>&nbsp;
                  	<span class="label label-info"><strong><?= Module::t('modules/finance/app', 'Initial Credit');?>:</strong>&nbsp;<?= Money::toCurrency(FinanceYear::getYearCredit(Yii::$app->session["working_year"]), true);?></span>
                  	<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#demo">
                  		<?= Module::t('modules/finance/app', 'More Information');?>
              		</button>
            	    <div id="demo" class="collapse">                   	
						<hr />
						<div class="row">
						    <?= ChartJs::widget([
                                    'type' => 'bar',
						            'options' => ['height' => $chart_height],
                                    'data' => [
                                        'labels' => $labels,
                                        'datasets' => [
                                            [
                                                'label' => Module::t('modules/finance/app', "Initial Credit"),                                                
                                                'backgroundColor' => 'orange',// $colors,
                                                'data' => $data
                                            ],
                                            [
                                                'label' => Module::t('modules/finance/app', "Available Credit Amount"),
                                                'backgroundColor' => 'blue', //$colors,
                                                'data' => $available
                                            ],
                                            [
                                                'label' => Module::t('modules/finance/app', "Withdrawals Sum"),
                                                'backgroundColor' => 'brown', //$colors,
                                                'data' => $withdrawals
                                            ],
                                            [
                                                'label' => Module::t('modules/finance/app', "Available for Usage"),
                                                'backgroundColor' => 'green', //$colors,
                                                'data' => $withdrawalsbalance
                                            ]
                                         ]
                                    ]
                                ]);
                            ?> 
						</div>
					</div>
                </div>
                
            </div>
        </div>
    </div>   
<?php endif;?>