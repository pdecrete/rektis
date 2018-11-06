<?php

use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\Disposal;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\HeadSignature\HeadSignatureWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */
/* @var $form yii\widgets\ActiveForm */



?>
<div class="disposal-approval-form container-fluid">
    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
    	<div class="col-lg-6"><?= $form->field($model, 'approval_regionaldirectprotocol')->textInput(['maxlength' => true]) ?></div>	
		<div class="col-lg-6"><?= $form->field($model, 'approval_regionaldirectprotocoldate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?></div>	
	</div>
	<div class="row">
    	<div class="col-lg-12"><?= $form->field($model, 'approval_notes')->textInput(['maxlength' => true]) ?></div>
    </div>
    <div class="row">
    	<div class="container col-lg-12">
    		<table class="table table-hover table-condensed">
    			<thead>
    				<tr>
    					<th></th><th>Εκπαιδευτικός</th><th>Ειδικότητα</th><th>Σχολείο Διάθεσης</th><th>Ώρες</th><th>Από</th><th>Έως</th>
    				</tr>
    				
    			</thead>    			
    			<?php foreach($disposals_models as $index=>$disposal_model): ?>
    				<tr>
    					<td>
							<?= $form->field($disposalapproval_models[$index], "[{$index}]disposal_id")->checkbox(['label' => '', 'value' => $disposalapproval_models[$index]['disposal_id']]); ?>
						</td>
						<td><?php echo $teacher_models[$index]['teacher_surname'] . ' ' . $teacher_models[$index]['teacher_name']; ?></td>
						<td><?php echo $specialization_models[$index]['code']; ?></td>
						<td><?php echo $school_models[$index]['school_name']; ?></td>
						<td><?php echo ($disposal_model['disposal_hours'] == Disposal::FULL_DISPOSAL) ? DisposalModule::t('modules/disposal/app', 'Full time Disposal') : $disposal_model['disposal_hours']; ?></td>
						<td><?php echo \Yii::$app->formatter->asDate($disposal_model['disposal_startdate']); ?></td>
						<td><?php echo \Yii::$app->formatter->asDate($disposal_model['disposal_enddate']); ?></td>
					</tr>        
    			<?php endforeach;?>
    		</table>
    	</div>
	</div>
	<?= HeadSignatureWidget::widget(['form' => $form, 'module' => Yii::$app->controller->module->id]);?>
	<br />
	<?php if($create): ?>
    	<div class="row">
    		<div class="col-lg-12"><?php echo Html::hiddenInput('disposal_ids', serialize($disposal_ids)); ?></div>
    	</div>
	<?php endif;?>
    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>