<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use app\modules\finance\models\FinanceSupplier;
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\finance\models\FinanceExpenditurestate;

?>
<div class="finance-expenditure-index">

    <p><strong><?= Module::t('modules/finance/app', 'Expedinture Payment Report') ?></strong></p>
    <p><strong><?= Module::t('modules/finance/app', 'For the needs of the Regional Directorate of Primary & Secondary Education of Crete') ?></strong></p>

	<table style="border:solid 1px;width:100%;">
		<tr>
			<td rowspan="2" style="text-align: center;"><?= Module::t('modules/finance/app', 'Beneficiary Details') ?></td>
			<td rowspan="2" style="text-align: center;"><?= Module::t('modules/finance/app', 'Voucher Number') ?></td>
			<td rowspan="2" style="text-align: center;"><?= Module::t('modules/finance/app', 'Rationale') ?></td>									
			<td colspan="3" style="text-align: center;"><?= Module::t('modules/finance/app', 'Expenditure Amount') ?></td>
			<td style="text-align: center;"><?= Module::t('modules/finance/app', 'Tax') ?></td>
			<td style="text-align: center;"><?= Module::t('modules/finance/app', 'Payable') ?></td>												
		</tr>
		<tr>
			<td style="text-align: center;"><?= Module::t('modules/finance/app', 'Net Value') ?></td>					
			<td style="text-align: center;"><?= Module::t('modules/finance/app', 'VAT') ?></td>
			<td style="text-align: center;"><?= Module::t('modules/finance/app', 'Sum') ?></td>
			<td></td>
			<td></td>												
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>									
			<td></td>
			<td></td>
			<td></td>
			<td></td>												
		</tr>
		<tr>
			<td>1</td>
			<td></td>
			<td></td>
			<td></td>									
			<td></td>
			<td></td>
			<td></td>
			<td></td>												
		</tr>						
	</table>

</div>