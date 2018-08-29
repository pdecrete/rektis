<?php

use app\modules\disposal\DisposalModule;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\Disposal */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($disposal_hours); echo "</pre>"; die();

/*
$script = "$(document).on('click', '#chkbox_endteachyear', function enabledisableDisposalendDatepicker(){
                var disposalend_datepicker = document.getElementById('disposalend_datepicker');

                $('#disposalend_datepicker').kvDatepicker('clearDates');alert('hallo');
                disposalend_datepicker.disabled = false;
                if(disposalend_datepicker.disabled){
                    disposalend_datepicker.disabled = false;
                }
                else {
                    disposalend_datepicker.disabled = true;
                }
                
           })";
$this->registerJs($script, View::POS_READY);
*/

//echo "<pre>"; print_r($disposal_reasons); echo "</pre>"; die();
?>
<?php $form = ActiveForm::begin(); ?>
<div class="disposal-form">
	<div class="row">
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_registrynumber')->textInput() ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_surname')->textInput() ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_name')->textInput() ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'specialisation_id')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map($specialisations, 'id', 'code'),
                                                'options' => ['placeholder' => Yii::t('app', 'Select specialisation...')],
                                            ])->label('Ειδικότητα'); ?>
        </div>		
	</div>
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($teacher_model, 'school_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
                                'options' => ['placeholder' => Yii::t('app', 'Select school...')],
                            ])->label('Σχολείο Υπηρέτησης'); ?>
        </div>
		<div class="col-lg-3">
			<?= $form->field($model, 'disposal_action')->textInput(); ?>
		</div>						        	
		<div class="col-lg-3">
			<?= $form->field($model, 'school_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
			                    'options' => ['placeholder' => DisposalModule::t('modules/disposal/app', 'Select disposal school...')],
                            ])->label('Σχολείο Διάθεσης'); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'disposal_hours')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map($disposal_hours, 'hours', 'hours_name'),
                                'options' => ['placeholder' => DisposalModule::t('modules/disposal/app', 'Select disposal hours...')],
                            ])->label('Ώρες Διάθεσης'); ?>
		</div>					
	</div>
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($model, 'disposal_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'disposal_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE, 'options' => ['id' => 'disposalend_datepicker']]); ?>
			<?= $form->field($model, 'disposal_endofteachingyear_flag')->checkbox(['label'=>'Λήξη στο τέλος του διδακτικού έτους', 'id' => 'chkbox_endteachyear']); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'disposalreason_id')->widget(Select2::classname(), [
			                     'data' => ArrayHelper::map($disposal_reasons, 'disposalreason_id', 'disposalreason_description'),
			                     'options' => ['placeholder' => DisposalModule::t('modules/disposal/app', 'For ...')],
                            ])->label('Αιτιολογία Διάθεσης'); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($model, 'disposalworkobj_id')->widget(Select2::classname(), [
			                     'data' => ArrayHelper::map($disposal_workobjs, 'disposalworkobj_id', 'disposalworkobj_description'),
			                     'options' => ['placeholder' => DisposalModule::t('modules/disposal/app', 'For ...')],
			                     'pluginOptions' => ['allowClear' => true]
                            ])->label('Αντικείμενο Εργασίας Διάθεσης'); ?>
		</div>			
	</div>


    <div class="form-group pull-right">
        <?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>        
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
