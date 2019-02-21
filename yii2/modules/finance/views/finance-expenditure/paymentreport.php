<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;

//foreach ($models as $model)
//echo "<pre>"; print_r($model['DEDUCTIONS']); echo "</pre>"; die();
//echo "<pre>"; print_r($models); echo "</pre>"; die();

$inline_th_css = 'style="text-align: center;border: 1px solid black;font-weight:bold;word-wrap:break-word;"';
$inline_th_css_min_width = 'style="text-align: center;border: 1px solid black;font-weight:bold;word-wrap:break-word;width:0px;"';
$inline_td_css_right = 'style="text-align: right;border: 1px solid black;"';
$inline_td_css_left = 'style="text-align: left;border: 1px solid black;"';
$sum_net_value = 0;
$sum_vat = 0;
$sum_taxes = 0;
$sum_payable_amount = 0;
$sum_expenditure_taxes = 0;
$allexpenditures_flat_taxes_sum = 0;

$deductions_array = [];
$deductions_array_sum = [];
$maxnum_deductions = 0;

$show_notes_column = false;
$show_flattaxes_column = false;
foreach ($models as $model) {
    if (count($model['DEDUCTIONS'] > $maxnum_deductions)) {
        $maxnum_deductions = count($model['DEDUCTIONS']);
    }
    foreach ($model['DEDUCTIONS'] as $deduction) {
        $deductions_array[$model['EXPENDITURE']['exp_id']][$deduction['deduct_name']] = $deduction['deduct_percentage'];
        if (!isset($deductions_array['SUM'][$deduction['deduct_name']])) {
            $deductions_array['SUM'][$deduction['deduct_name']] = [];
            $deductions_array['SUM'][$deduction['deduct_name']]['SUM_AMOUNT'] = 0;
            $deductions_array['SUM'][$deduction['deduct_name']]['PERCENTAGE'] = (Money::toPercentage($deduction['deduct_percentage'], true));
        }
        $deductions_array['SUM'][$deduction['deduct_name']]['SUM_AMOUNT'] += (Money::toPercentage($deduction['deduct_percentage'], false)/100)*Money::toCurrency($model['EXPENDITURE']['exp_amount']);
    }
    if(trim($model['EXPENDITURE']['exp_notes']) != '')
        $show_notes_column = true;
    if(count($model['EXPENDITURE']['flat_taxes']) > 0)
        $show_flattaxes_column = true;
}

$greek_logo = "file:///" . realpath(Yii::getAlias('@images/greek_logo.png'));
?>
<div class="finance-expenditure-index">
    <table style="border: 0px; padding: 5 5 5 5px;">
    	<tr><td colspan="2" style="text-align:center"><?= '<img src=' . $greek_logo . '>' ?><h5><strong><?= Yii::$app->params['pde_logo_literal']; ?><br />
			<?= Yii::$app->params['finance_logo_literal']; ?></strong></h5></td><td></td></tr>
	</table>
	<!--p><?= '<img src=' . $greek_logo . '>' ?></p-->
    <p><strong><?= Module::t('modules/finance/app', 'Expedinture Payment Report') ?> </strong>
               <?= '(' . Module::t('modules/finance/app', 'RCN') . ((strlen($kae) <= 4) ? sprintf('%04d', $kae) : $kae)
                    . ' - ' . Module::t('modules/finance/app', 'Financial Year')
                    . ' ' . $year . ')'
                ?></p>
    <p><strong><?= Module::t('modules/finance/app', 'For the needs of the Regional Directorate of Primary & Secondary Education of Crete') ?></strong></p>

	<table style="width:100%;border-collapse: collapse;">
		<tr>
			<td rowspan="2" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Beneficiary Details') ?></td>
			<td rowspan="2" <?= $inline_th_css_min_width?>><?= Module::t('modules/finance/app', 'Voucher Number') ?></td>
			<td rowspan="2" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Rationale') ?></td>
		<?php if($show_flattaxes_column): ?>
			<td colspan="5" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Expenditure Amount') ?></td>
		<?php else:?>
			<td colspan="3" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Expenditure Amount') ?></td>
		<?php endif;?>
		<?php   foreach ($deductions_array['SUM'] as $key=>$value):?>
					<td <?= $inline_th_css?>><?= $key ?></td>
		<?php   endforeach;?>
			<td rowspan="2" <?= $inline_th_css_min_width?>><?= Module::t('modules/finance/app', 'Payable Amount') ?></td>												
		</tr>
		<tr>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Net Value') ?></td>					
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'VAT') ?></td>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Value after VAT') ?></td>
			<?php if($show_flattaxes_column): ?>
    			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Λοιποί<br />Φόροι') ?></td>
    			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Notes') ?></td>
			<?php endif;?>
			<?php   foreach ($deductions_array['SUM'] as $key=>$value):?>
						<td <?= $inline_th_css_min_width?>><?= $value['PERCENTAGE'] ?></td>										
			<?php   endforeach;?>			
		</tr>
		<?php   foreach ($models as $model):
                    $net_value = Money::toCurrency($model['EXPENDITURE']['exp_amount']);
                    $vat = $net_value * Money::toDecimalPercentage($model['EXPENDITURE']['fpa_value']);

                    $flat_taxes_sum = 0;
                    if($show_flattaxes_column) {
                        foreach ($model['EXPENDITURE']['flat_taxes'] as $flattax) {
                            $flat_taxes_sum += Money::toCurrency($flattax);
                        }
                    }
                    $sum_expenditure_taxes = 0;
                    $payable_amount = $net_value + $vat + $flat_taxes_sum;?>				
            		<tr>
            			<td <?= $inline_td_css_left?>><?= $model['SUPPLIER']['suppl_name']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $model['INVOICE']['inv_number']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $model['EXPENDITURE']['exp_description']; ?></td>
            			<td <?= $inline_td_css_right?>><?= number_format($net_value, 2, ',', '.') ?></td>
            			<td <?= $inline_td_css_right?>><?= number_format($vat, 2, ',', '.') ?></td>
            			<td <?= $inline_td_css_right?>><?= number_format($net_value + $vat, 2, ',', '.') ?></td>
		<?php           if($show_flattaxes_column): ?>
							<td <?= $inline_td_css_right?>>
								<?php   foreach ($model['EXPENDITURE']['flat_taxes'] as $flattax):
                                            echo number_format(Money::toCurrency($flattax), 2, ',', '.') . "<br />";
								        endforeach;       
							    ?>
							</td>
							<td <?= $inline_td_css_left?>><?= $model['EXPENDITURE']['exp_notes']; ?></td>
		<?php           endif; ?>
		<?php           foreach ($deductions_array['SUM'] as $key=>$value):
                            if (isset($deductions_array[$model['EXPENDITURE']['exp_id']][$key])):
                                $tax = Money::toCurrency($model['EXPENDITURE']['exp_amount'], false)*Money::toPercentage($deductions_array[$model['EXPENDITURE']['exp_id']][$key], false)/100;
                                $sum_expenditure_taxes += $tax;?>
                                <td <?= $inline_td_css_right?>><?= number_format($tax, 2, ',', '.'); ?></td>
		<?php               else: ?>
								<td <?= $inline_td_css_right?>></td>                                
       	<?php               endif;?>                    		    
		<?php           endforeach;?>
        				<td <?= $inline_td_css_right?>><?= number_format($payable_amount - $sum_expenditure_taxes, 2, ',', '.') ?></td>
        			</tr>
        <?php       $sum_net_value += $net_value;
                    $sum_vat += $vat;
                    $sum_payable_amount += $payable_amount - $sum_expenditure_taxes;
                    $allexpenditures_flat_taxes_sum += $flat_taxes_sum;
                endforeach;
                        
        ?>
                
		<tr>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Sum') ?></td>
			<td <?= $inline_td_css_right?>></td>
			<td <?= $inline_td_css_right?>></td>
			<td <?= $inline_td_css_right?>><?= number_format($sum_net_value, 2, ',', '.') ?></td>							
			<td <?= $inline_td_css_right?>><?= number_format($sum_vat, 2, ',', '.') ?></td>
			<td <?= $inline_td_css_right?>><?= number_format($sum_net_value + $sum_vat, 2, ',', '.') ?></td>
    		<?php if($show_flattaxes_column): ?>
    			<td <?= $inline_td_css_right?>><?= number_format($allexpenditures_flat_taxes_sum, 2, ',', '.') ?></td>
    			<td <?= $inline_td_css_right?>></td>
    		<?php endif;?>

		<?php   foreach ($deductions_array['SUM'] as $key=>$value):?>
					<td <?= $inline_td_css_right?>><?= number_format($value['SUM_AMOUNT'], 2, ',', '.') ?></td>										
		<?php   endforeach;?>
			<td <?= $inline_td_css_right?>><?= number_format($sum_payable_amount, 2, ',', '.') ?></td>
		</tr>						
	</table>
	<br /><br />
	<table style="width:100%;border: 0;">
		<tr>
			<td style="text-align:right;width:25%;border: 0;">&nbsp;</td>
			<td style="text-align:right;width:25%;border: 0;">&nbsp;</td>
			<td style="text-align:center;width:50%;border: 0;">
				<p>ΒΕΒΑΙΩΝΕΤΑΙ Η ΠΡΑΓΜΑΤΟΠΟΙΗΣΗ ΤΗΣ ΠΑΡΑΠΑΝΩ ΔΑΠΑΝΗΣ</p>
				<p>&nbsp;</p>
				<p><strong>Ηράκλειο, <?= date('d/m/Y', strtotime($maxdate));?></strong></p>
				<p><strong><?= Yii::$app->params['director_sign'] ?></strong><br /></p>
				<p><strong><?= Yii::$app->params['director_name']; ?></strong></p>
			</td>			
	</table>
</div>














