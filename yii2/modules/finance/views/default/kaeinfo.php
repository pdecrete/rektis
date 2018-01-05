<?php 
use app\modules\finance\Module;
use app\modules\finance\components\Money;


$balance = (Money::toCurrency($kaeCredit->kaecredit_amount)*Money::toPercentage($kaeCreditSumPercentage, false))/100;
$withdrawalsSum = 0;
?>

<p>
    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#kaeInfo">
    	<?php echo Module::t('modules/finance/app', 'RCN {kae_id} - Quick Info', ['kae_id' => $kae->kae_id]);?>
    </button>
</p>
<div id="kaeInfo" class="collapse"  style="width: 50% !important;">
    <div class="container-fluid well">
  		<div class="row">
        <table class="table table-hover">
            <thead><tr><th class="text-center" colspan="2"><?php echo "ΚΑΕ " . $kae->kae_id . " - " . $kae->kae_title  ?></th></tr></thead>
            <tr class="info"><td><?= Module::t('modules/finance/app', 'RCN Initial Credit') ?>:</td><td class="text-right"><?= Money::toCurrency($kaeCredit->kaecredit_amount) ?></td></tr>
            <tr class="info"><td><?= Module::t('modules/finance/app', 'Συνολικό Ποσοστό Διάθεσης') ?>:</td><td class="text-right"><?= Money::toPercentage($kaeCreditSumPercentage) ?></td></tr>
            <tr class="info"><td><?= Module::t('modules/finance/app', 'Διαθέσιμο ποσό') ?>:</td><td class="text-right"><?= $balance ?></td></tr>
            <?php foreach ($kaeWithdrwals as $withdrawal) :
            		$withdrawalsSum += $withdrawal->kaewithdr_amount; ?>
            		<tr class="danger"><td>Ανάληψη (<?= $withdrawal->kaewithdr_date ?>):</td><td class="text-right"><?= Money::toCurrency($withdrawal->kaewithdr_amount) ?></td></tr>
            <?php endforeach;?>
            <tr class="success"><td><strong>Διαθέσιμο Υπόλοιπο για Ανάληψη:</strong></td><td class="text-right"><strong><?= ($balance - Money::toCurrency($withdrawalsSum)) ?></strong></td></tr>	
        </table>
		</div>
	</div>
</div>