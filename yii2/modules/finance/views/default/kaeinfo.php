<?php 
use app\modules\finance\Module;
use app\modules\finance\components\Money;
use dosamigos\chartjs\ChartJs;

$balance = Money::toCurrency($kaeCredit->kaecredit_amount)*Money::toPercentage($kaeCreditSumPercentage, false);
$balance_formatted = Money::toCurrency(Money::toCurrency($kaeCredit->kaecredit_amount)*Money::toPercentage($kaeCreditSumPercentage, false), true);
$withdrawalsSum = 0;
?>
<?php $collpased = ($options['collapsed'] == 1)? 'in': ''; ?>
<?php if($options['showbutton']) :?>
<p>
    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#kaeInfo">
    	<?php echo Module::t('modules/finance/app', 'RCN {kae_id} - Quick Info', ['kae_id' => sprintf('%04d', $kae->kae_id)]);?>
    </button>
</p>
<?php endif;?>

<div id="kaeInfo" class="collapse <?= $collpased ?>">
    <div class="container-fluid well">
  		<div class="row">
        <table class="table table-hover">
            <thead><tr><th class="text-center" colspan="2"><?php echo Module::t('modules/finance/app', 'RCN') . ' ' . sprintf('%04d', $kae->kae_id) . " - " . $kae->kae_title  ?></th></tr></thead>
            <tr class="info"><td><?= Module::t('modules/finance/app', 'RCN Initial Credit') ?>:</td><td class="text-right"><?= Money::toCurrency($kaeCredit->kaecredit_amount, true) ?></td></tr>
            <tr class="info"><td><?= Module::t('modules/finance/app', 'Total Attributed Percentage') ?>:</td><td class="text-right"><?= Money::toPercentage($kaeCreditSumPercentage) ?></td></tr>
            <tr class="info"><td><?= Module::t('modules/finance/app', 'Available Amount') ?>:</td><td class="text-right"><?= $balance_formatted ?></td></tr>
            <?php foreach ($kaeWithdrwals as $withdrawal) :
            		$withdrawalsSum += $withdrawal->kaewithdr_amount; ?>
            		<tr class="danger"><td><?= Module::t('modules/finance/app', 'Withdrawal')?> (<?= $withdrawal->kaewithdr_date ?>):</td><td class="text-right"><?= Money::toCurrency($withdrawal->kaewithdr_amount, true) ?></td></tr>
            <?php endforeach;?>
            <tr class="success"><td><strong><?= Module::t('modules/finance/app', 'Available Amount for Withdrawal')?>:</strong></td><td class="text-right"><strong><?= Money::toCurrency($balance - $withdrawalsSum, true) ?></strong></td></tr>	
        </table>
		</div>
	</div>
	
	<div>
		<?php 
		      $labels = [ Module::t('modules/finance/app', 'RCN Initial Credit'), 
		                  Module::t('modules/finance/app', 'Unallocated Amount'),
		                  Module::t('modules/finance/app', 'Available Amount'),
		                  Module::t('modules/finance/app', 'Withdrawals Sum'), 
		                  Module::t('modules/finance/app', 'Available Amount for Withdrawal')
		                ];
		      
		      $data = [   Money::toCurrency($kaeCredit->kaecredit_amount), 
		                  Money::toCurrency($kaeCredit->kaecredit_amount - $balance),
		                  Money::toCurrency($balance),
		                  Money::toCurrency($withdrawalsSum), 
		                  Money::toCurrency($balance - $withdrawalsSum)		          
		              ];
		      
		      $colors = ['#5bc0de', '#d9edf7', '#afd9ee', '#e4b9b9', '#c1e2b3'];
		      
		      echo ChartJs::widget([  'type' => 'bar',
                                      'data' => [ 'labels' => $labels,
                                                    'datasets' => [
                                                                    [ 
                                                                     'label' => '',// Module::t('modules/finance/app', 'RCN') . ' ' . sprintf('%04d', $kae->kae_id),
                                                                     'backgroundColor' => $colors,
                                                                     'data' => $data],
                                                                    ],
                                                    'options' => ['legends' => ['display' => false]]
                ]
            ]);
        ?>
	</div>
</div>