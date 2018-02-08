<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use app\modules\finance\models\FinanceSupplier;
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\finance\models\FinanceExpenditurestate;

//echo "<pre>"; print_r($models); echo "</pre>"; die();

$inline_th_css = 'style="text-align: center;border: 1px solid black;font-weight:bold;"';
$inline_td_css_right = 'style="text-align: right;border: 1px solid black;"';
$inline_td_css_left = 'style="text-align: left;border: 1px solid black;"';
$sum_net_value = 0;
$sum_vat = 0;
$sum_taxes = 0;
$sum_payable_amount = 0;

$financial_logo = "file:///" . realpath(Yii::getAlias('@images/financial_logo.png'));
?>
<div class="finance-expenditure-index">
	<p><?= '<img src=' . $financial_logo . '>' ?></p>
    <p><strong><?= Module::t('modules/finance/app', 'Expedinture Payment Report') ?> </strong>
               <?= '(' . Module::t('modules/finance/app', 'RCN') . sprintf('%04d', $kae) 
                    . ' - ' . Module::t('modules/finance/app', 'Financial Year')  
                    . ' ' . $year . ')'
                ?></p>
    <p><strong><?= Module::t('modules/finance/app', 'For the needs of the Regional Directorate of Primary & Secondary Education of Crete') ?></strong></p>

	<table style="width:100%;border-collapse: collapse;">
		<tr>
			<td rowspan="2" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Beneficiary Details') ?></td>
			<td rowspan="2" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Voucher Number') ?></td>
			<td rowspan="2" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Rationale') ?></td>									
			<td colspan="3" <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Expenditure Amount') ?></td>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Tax') ?></td>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Payable Amount') ?></td>												
		</tr>
		<tr>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Net Value') ?></td>					
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'VAT') ?></td>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Sum') ?></td>
			<td <?= $inline_td_css_right?>>
			<td <?= $inline_td_css_right?>>						
		</tr>
		<?php   foreach($models as $model): 
    		        $net_value = Money::toCurrency($model['EXPENDITURE']['exp_amount']);
    		        $vat = $net_value * (Money::toPercentage($model['EXPENDITURE']['fpa_value'])/100);
    		        $taxes = 0;
    		        $payable_amount = $net_value + $vat + $taxes;
  		?>				
            		<tr>
            			<td <?= $inline_td_css_left?>><?= $model['SUPPLIER']['suppl_name']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $model['INVOICE']['inv_number']; ?></td>
            			<td <?= $inline_td_css_left?>><?= $model['EXPENDITURE']['exp_description']; ?></td>
            			<td <?= $inline_td_css_right?>><?= number_format($net_value, 2, ',', '.') ?></td>
            			<td <?= $inline_td_css_right?>><?= number_format($vat, 2, ',', '.') ?></td>
            			<td <?= $inline_td_css_right?>><?= number_format($net_value + $vat, 2, ',', '.') ?></td>
            			<td <?= $inline_td_css_right?>></td>
            			<td <?= $inline_td_css_right?>><?= number_format($payable_amount, 2, ',', '.') ?></td>
            		</tr>
        <?php
                    $sum_net_value += $net_value;
                    $sum_vat += $vat;
                    $sum_taxes = 0;
                    $sum_payable_amount += $payable_amount;
                endforeach; ?>
		<tr>
			<td <?= $inline_th_css?>><?= Module::t('modules/finance/app', 'Sum') ?></td>
			<td <?= $inline_td_css_right?>></td>
			<td <?= $inline_td_css_right?>></td>
			<td <?= $inline_td_css_right?>><?= number_format($sum_net_value, 2, ',', '.') ?></td>							
			<td <?= $inline_td_css_right?>><?= number_format($sum_vat, 2, ',', '.') ?></td>
			<td <?= $inline_td_css_right?>><?= number_format($sum_net_value + $sum_vat, 2, ',', '.') ?></td>
			<td <?= $inline_td_css_right?>><?= number_format($sum_taxes, 2, ',', '.') ?></td>
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
				<p><strong>Ηράκλειο, <?= date("d-m-Y")?></strong></p>
				<p><strong><?= Yii::$app->params['director_sign'] ?></strong></p>
			</td>			
	</table>
</div>














