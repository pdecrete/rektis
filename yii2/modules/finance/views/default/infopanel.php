<?php
use app\modules\finance\Module;
use app\modules\finance\models\FinanceYear;
use app\modules\finance\components\Integrity;
use app\modules\finance\components\Money;
use dosamigos\chartjs\ChartJs;
use app\modules\finance\models\FinanceKaecredit;

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}

if(Integrity::uniqueCurrentYear()):
    $credits = FinanceKaecredit::find()->where(['year' => Yii::$app->session["working_year"]])->all();
    $labels = array();
    $data = array();
    $colors = array();
    foreach ($credits as $index=>$credit){
        if($credit->kaecredit_amount != 0){
            $labels[$index] = sprintf('%04d', $credit->kae_id);
            $data[$index] = Money::toCurrency($credit->kaecredit_amount);
            $colors[$index] = '#' . random_color();
        }
    }
    
    //echo "<pre>"; print_r($data); echo "</pre>"; die();
    
?>
    <div class="row">
    
    	<div class="col-lg-4">&nbsp;</div>
    	<div class="col-lg-4">&nbsp;</div>
    	<div class="col-lg-4">
        	<div class="panel panel-default">
            	<div class="panel-heading">
                  	<span>
                  		<?= Module::t('modules/finance/app', 'Currently Working Year');?><strong>: <?= Yii::$app->session["working_year"] ?></strong>
                  	</span>
                  	&nbsp;&nbsp;&nbsp;
                  	<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#demo">
                  		<?= Module::t('modules/finance/app', 'Information');?>
              		</button>
            	    <div id="demo" class="collapse">
                    	<hr />
                        <div class="row">
                            <div class="col-lg-6"><strong><?= Module::t('modules/finance/app', 'Initial Credit');?>:</strong></div>
                            <div class="col-lg-6"><?= Money::toCurrency(FinanceYear::getYearCredit(Yii::$app->session["working_year"]), true);?></div>
						</div>
						<hr />
						<div class="row">
						    <?= ChartJs::widget([
                                    'type' => 'horizontalBar',   
                                    'data' => [
                                        'labels' => $labels,
                                        'datasets' => [
                                            [
                                                'label' => Module::t('modules/finance/app', "RCN Credits"),                                                
                                                'backgroundColor' => $colors,
                                                'data' => $data
                                            ],
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