<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($model); echo "</pre>"; 

?>

<?php   $form = ActiveForm::begin(['id' => 'kaes-form', 'layout' => 'horizontal']); ?>
        	<table class="table table-striped">
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
                                                                					    'value' => intval($model[$index]->kaecredit_amount)/100])->label(false); 
                        ?>
				    </td>									
				</tr>
<?php           endforeach;?>
				<tr>
					<td colspan="3"  class="text-right"><?= Html::submitButton('Αποθήκευση Πιστώσεων', ['class' => 'btn btn-success btn-lg pull-right']) ?></td>
				</tr>
        	</table>
<?php   ActiveForm::end(); ?>
