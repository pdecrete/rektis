<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($model); echo "</pre>"; die();

?>

<?php   $form = ActiveForm::begin(['id' => 'kaes-form', 'layout' => 'horizontal']); ?>

        	<table class="table table-striped" style="margin: 10px;">
        		<tr>
        			<th class="text-center">ΚΑΕ</th>
        			<th class="text-center">Τίτλος ΚΑΕ</th>
        			<th class="text-center">Πίστωση</th>
        		</tr>
<?php           foreach($kaetitles as $index => $kaetitle):?>
				<tr>
					<td class="text-center"><?php echo $model[$index]->kae_id ?></td>
					<td><?php echo $kaetitle ?></td>
					<td class="text-center">
						<?= $form->field($model[$index], "[{$index}]kaecredit_amount")->textInput(['maxlength' => true, 
                                                                					    'type' => 'number', 
                                                                					    'min' => "0.00" , 
                                                                					    'step' => '0.01', 
                                                                					    'style' => 'text-align: right', 
						    'value' => Money::toCurrency($model[$index]->kaecredit_amount)])->label(false); 
                        ?>
				    </td>									
				</tr>
<?php           endforeach;?>
        	</table>
        	<div class="col-lg-12 text-right">
				<?= Html::submitButton(Yii::t('app', 'Save RCN credits'), ['class' => 'btn btn-success btn-lg pull-right'])?>
			</div>
<?php   ActiveForm::end(); ?>

