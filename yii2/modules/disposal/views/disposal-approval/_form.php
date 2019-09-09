<?php

use app\modules\base\widgets\HeadSignature\HeadSignatureWidget;
use app\modules\disposal\DisposalModule;
use app\modules\disposal\models\Disposal;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */
/* @var $form yii\widgets\ActiveForm */

//echo "<pre>"; print_r($model); echo "<pre>"; die();

?>
<div class="disposal-approval-form container-fluid">
    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<?php if ((isset($republish) && $republish == 1) || $model->getRepublishedApproval() != null): ?>
    		<div class="col-lg-6"><?= $form->field($model, 'approval_regionaldirectprotocol')->textInput(['maxlength' => true, 'disabled' => true]) ?></div>
    	<?php else: ?>
    		<div class="col-lg-6"><?= $form->field($model, 'approval_regionaldirectprotocol')->textInput(['maxlength' => true])?></div>
    	<?php endif;?>	
		<div class="col-lg-6"><?= $form->field($model, 'approval_regionaldirectprotocoldate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?></div>	
	</div>
	<div class="row">
    	<div class="col-lg-12"><?= $form->field($model, 'approval_notes')->textInput(['maxlength' => true]) ?></div>    	
    </div>
    
    <?php if ((isset($republish) && $republish == 1) || $model->getRepublishedApproval() != null): ?>
    	<div class="row">
        	<div class="col-lg-9"><?= $form->field($model, 'approval_republishtext')->textInput(['maxlength' => true]) ?></div>
        	<div class="col-lg-3"><?= $form->field($model, 'approval_republishdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?></div>
        </div>
    <?php endif;?>
    
    <div class="row">
    	<div class="container col-lg-12">
    		<?php if (isset($republish) && $republish == 1):
                      $schools_mapping = ArrayHelper::map($schools, 'school_id', 'school_name');
                      $hours_mapping = ArrayHelper::map($disposal_hours, 'hours', 'hours_name');
                      $days_mapping = ArrayHelper::map($disposal_days, 'days', 'days_name');
                      $reasons_mapping = ArrayHelper::map($disposal_reasons, 'disposalreason_id', 'disposalreason_description');
                      $duties_mapping = ArrayHelper::map($disposal_duties, 'disposalworkobj_id', 'disposalworkobj_description');
            ?>
    				
        		<table class="table table-bordered table-striped table-hover table-condensed text-center">
        							<tr class="info">
        								<td rowspan="2"></td>
        								<td colspan="2"><strong>Εκπαιδευτικός</strong></td><td colspan="2"><strong>Σχολείο Υπηρέτησης</strong></td><td colspan="2"><strong>Σχολείο Διάθεσης</strong></td>
        							</tr>
        							<tr class="info">
        								<td><strong>Ώρες</strong></td><td><strong>Ημέρες</strong></td><td><strong>Από</strong></td><td><strong>Έως</strong></td><td><strong>Λόγος</strong></td><td><strong>Καθήκον</strong></td>	
        							</tr>
        							</table>
        			<?php foreach ($disposals_models as $index=>$disposal_model): ?>
        			<table class="table table-bordered table-striped table-hover table-condensed text-center">
    							<tr>
    								<td rowspan="2" class="text-center" style="vertical-align: middle;">
    									<?= $form->field($disposalapproval_models[$index], "[{$index}]disposal_id")->checkbox(['label' => '', 'value' => $disposalapproval_models[$index]['disposal_id']]); ?>
									</td>
    								<td colspan="2">
    									<?php echo $teacher_models[$index]['teacher_surname'] . ' ' . $teacher_models[$index]['teacher_name'] . ', ' . $specialization_models[$index]['code']; ?>
									</td>
    								<td colspan="2">
    									<?= $form->field($disposal_model, '['.$index.']fromschool_id')->widget(Select2::classname(), ['data' => $schools_mapping])->label(false); ?>
    								</td>
    								<td colspan="2">
    									<?= $form_fields_toschool[$index] = $form->field($disposal_model, '['.$index.']toschool_id')->widget(Select2::classname(), ['data' => $schools_mapping])->label(false) ?>
									</td>
							            
    							</tr>
    							<tr>
    								<td><?= $form->field($disposal_model, '['.$index.']disposal_hours')
                                                ->widget(Select2::classname(), ['data' => $hours_mapping])->label(false); ?></td>
    								<td><?= $form->field($disposal_model, '['.$index.']disposal_days')
                                                ->widget(Select2::classname(), ['data' => $days_mapping])->label(false); ?></td>
    								<td><?= $form->field($disposal_model, '['.$index.']disposal_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE])->label(false); ?></td>
    								<td><?= $form->field($disposal_model, '['.$index.']disposal_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE])->label(false); ?></td>    								
    								<td><?= $form->field($disposal_model, '['.$index.']disposalreason_id')
                                                ->widget(Select2::classname(), ['data' => $reasons_mapping])->label(false); ?>
						            </td>
    								<td><?= $form->field($disposal_model, '['.$index.']disposalworkobj_id')
                                                ->widget(Select2::classname(), ['data' => $duties_mapping])->label(false); ?>
						            </td>	
    							</tr>        					
						</table>
        			<?php endforeach;?>
        		
    		<?php else:?>
        		<table class="table table-bordered table-striped table-hover table-condensed">
        			<thead>
        				<tr>
        					<th></th><th>Εκπαιδευτικός</th><th>Ειδικότητα</th><th>Σχολείο Υπηρέτησης</th><th>Σχολείο Διάθεσης</th><th>Ημέρες</th><th>Ώρες</th><th>Από</th><th>Έως</th><th>Λόγος</th><th>Καθήκον</th>
        				</tr>
        				
        			</thead>    			
        			<?php foreach ($disposals_models as $index=>$disposal_model): ?>
        				<tr>        					
        					<td>
    							<?= $form->field($disposalapproval_models[$index], "[{$index}]disposal_id")->checkbox(['label' => '', 'value' => $disposalapproval_models[$index]['disposal_id']]); ?>
    						</td>
    						<td><?php echo $teacher_models[$index]['teacher_surname'] . ' ' . $teacher_models[$index]['teacher_name']; ?></td>
    						<td><?php echo $specialization_models[$index]['code']; ?></td>
    						<td><?php echo $fromschool_models[$index]['school_name']; ?></td>
    						<td><?php echo $toschool_models[$index]['school_name']; ?></td>
    						<td><?php echo ($disposal_model['disposal_days'] == Disposal::FULL_DISPOSAL) ? DisposalModule::t('modules/disposal/app', 'Full time Disposal') : $disposal_model['disposal_days']; ?></td>
    						<td><?php echo ($disposal_model['disposal_hours'] == Disposal::FULL_DISPOSAL) ? DisposalModule::t('modules/disposal/app', 'Full time Disposal') : $disposal_model['disposal_hours']; ?></td>
    						<td><?php echo \Yii::$app->formatter->asDate($disposal_model['disposal_startdate']); ?></td>
    						<td><?php echo \Yii::$app->formatter->asDate($disposal_model['disposal_enddate']); ?></td>
    						<td><?php echo $reason_models[$index]['disposalreason_description']; ?></td>
    						<td><?php echo $duty_models[$index]['disposalworkobj_description']; ?></td>
    					</tr>        
        			<?php endforeach;?>
        		</table>
    		<?php endif;?>
    	</div>
	</div>
	<div>
		<div class="col-lg-12"><strong>(Εμφανιζόμενες Διαθέσεις: <?= $index+1?>)</strong></div>
	</div>
	<?= HeadSignatureWidget::widget(['form' => $form, 'module' => Yii::$app->controller->module->id]);?>
	<br />
	<?php if (isset($create) && $create): ?>
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