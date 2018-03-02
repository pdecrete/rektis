<?php

use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\bootstrap\ActiveForm;
use app\modules\finance\Module;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($model); echo "</pre>"; die();

?>
<div class="finance-kaecredits-form">
<?php   $form = ActiveForm::begin(['id' => 'kaes-form', 'layout' => 'horizontal']); ?>

        	<table class="table table-striped" style="margin: 10px;">
        		<tr>
        			<th class="text-center">ΚΑΕ</th>
        			<th class="text-center">Τίτλος ΚΑΕ</th>
        			<th class="text-center">Πίστωση</th>
        		</tr>
<?php           foreach($kaetitles as $index => $kaetitle):?>
				<tr>
					<td class="text-center"><?php echo sprintf('%04d', $model[$index]->kae_id); ?></td>
					<td><?php echo $kaetitle ?></td>
					<td class="text-center">
						<?= $form->field($model[$index], "[{$index}]kaecredit_amount")->textInput(['maxlength' => true, 
                                                                					    'type' => 'number', 
                                                                					    'min' => "0.00" , 
                                                                					    'step' => '0.01', 
                                                                					    'style' => 'text-align: right', 
						    'value' => $model[$index]->kaecredit_amount/100])->label(false); 
                        ?>
				    </td>									
				</tr>
<?php           endforeach;?>
        	</table>
        	<div class="form-group pull-right">
        		<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
				<?= Html::submitButton(Module::t('modules/finance/app', 'Save Credits'), ['class' => 'btn btn-success'])?>
			</div>
<?php   ActiveForm::end(); ?>
</div>

