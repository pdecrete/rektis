<?php

use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use app\modules\finance\Module;
use app\modules\finance\components\Money;
use app\modules\finance\models\FinanceYear;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($model); echo "</pre>"; die();
//echo "<pre>"; print_r($kaetitles); echo "</pre>"; die();

$script = "var oldvalue = 0;          

           function storeOldValue(element){
                checkValue = Number(element.value); 
                if(isNaN(checkValue)) 
                    return;

                oldvalue = checkValue;
            }

           function updateSumCreditField(element, yearCredit){
                newValue = Number(element.value);
                if(isNaN(newValue)){
                    document.getElementById('sumCreditTblRow').className = 'danger';
                    return;
                }
                newSumCredit = Number(document.getElementById('sumCredits').innerHTML) - oldvalue + newValue;
                oldvalue = 0;
                document.getElementById('sumCredits').innerHTML = newSumCredit;

                if(newSumCredit == Number(yearCredit))
                    document.getElementById('sumCreditTblRow').className = 'success';
                else
                    document.getElementById('sumCreditTblRow').className = 'danger';
                
           }";
$this->registerJs($script, View::POS_HEAD);

$yearCredit = Money::toCurrency(FinanceYear::findOne(['year' => Yii::$app->session["working_year"]])->year_credit);

$sumCredits = 0;
foreach ($model as $uniqueModel)
    $sumCredits += $uniqueModel['kaecredit_amount'];
$sumCredits = Money::toCurrency($sumCredits);
?>
<div class="finance-kaecredits-form">
<?php   $form = ActiveForm::begin(['id' => 'kaes-form', 'layout' => 'horizontal']); ?>

        	<table class="table table-striped" style="margin: 10px;">
        		<tr>
        			<th class="text-center">ΚΑΕ</th>
        			<th class="text-center">Τίτλος ΚΑΕ</th>
        			<th class="text-center">Πίστωση</th>
        		</tr>
<?php           foreach ($kaetitles as $index => $kaetitle):?>
				<tr>
					<td class="text-center"><?php echo sprintf('%04d', $model[$index]->kae_id); ?></td>
					<td><?php echo $kaetitle ?></td>
					<td class="text-center">
						<?= $form->field($model[$index], "[{$index}]kaecredit_amount")
						         ->textInput(['maxlength' => true,
                                              //'type' => 'number',
                                              //'min' => "0.00" ,
                                              //'step' => '0.01',
                                              'style' => 'text-align: right',
                                              'value' => $model[$index]->kaecredit_amount/100,
						                      'onchange' => 'updateSumCreditField(this,' . $yearCredit .');',
						                      'onclick' => 'storeOldValue(this);'
						                     ])->label(false);
                        ?>
				    </td>									
				</tr>
<?php           endforeach;

                $sumCreditTblRowClass = ($sumCredits == $yearCredit)?'success':'danger';
?>

				<tr id="sumCreditTblRow" class=<?= $sumCreditTblRowClass ?>>
					<td class="text-left">&nbsp;</td>
        			<td class="text-left"><strong>ΣΥΝΟΛΟ</strong></td>
        			<td class="text-center"><strong><span id="sumCredits"><?= $sumCredits ?></span></strong></td>
				</tr>
        	</table>
        	<div class="form-group pull-right">
        		<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
				<?= Html::submitButton(Module::t('modules/finance/app', 'Save Credits'), ['class' => 'btn btn-success'])?>
			</div>
<?php   ActiveForm::end(); ?>
</div>

