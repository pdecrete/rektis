<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */

$this->title = Yii::t('app', 'Create Finance Kaecredit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaecredits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>"; print_r($model); echo "</pre>";die();
?>
<div class="finance-kaecredit-create">

    <h1><?= Html::encode($this->title) ?></h1>

 <?php  $form = ActiveForm::begin(['id' => 'kaes-form', 'layout' => 'horizontal']); ?>
 			
        	<table class="table table-striped">
        		<tr>
        			<th class="text-center">ΚΑΕ</th>
        			<th class="text-center">Τίτλος ΚΑΕ</th>
        			<th class="text-center">Πίστωση</th>
        		</tr>
<?php           foreach($model as $index => $kae):?>
				<tr>
					<td class="text-center"><?php echo $kae->kae_id ?></td>
					<td><?php echo $kaetitles[$index] ?></td>
					<td class="text-center">
						<?= $form->field($kae, "[{$index}]kaecredit_amount")->textInput(['maxlength' => true, 
                                                                					    'type' => 'number', 
                                                                					    'min' => "0.00" , 
                                                                					    'step' => '0.10', 
                                                                					    'style' => 'text-align: right', 
                                                                					    'value' => '0.00'])->label(false); 
                        ?>
				    </td>									
				</tr>
<?php           endforeach;?>
				<tr>
					<td colspan="3"  class="text-right"><?= Html::submitButton('Αποθήκευση Πιστώσεων', ['class' => 'btn btn-success btn-lg pull-right']) ?></td>
				</tr>
        	</table>
<?php   ActiveForm::end(); ?>

</div>
