<?php

use app\modules\disposal\DisposalModule;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

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

$ajaxscript_searchTeacherById = 'function searchTeacherById(argument){
                                    var regNumber = document.getElementById("teacher_regnumber_frmid").value;
                                    $.ajax({ 
                                            url: argument, 
                                            type: "post", 
                                            data: {"regNumber":regNumber},
                                            success: function(data){
                                                        data = JSON.parse(data);
                                                        if(data == null) {
                                                            $("#teacher_surname_frmid").prop("disabled", false);
                                                            $("#teacher_name_frmid").prop("disabled", false);
                                                            $("#teacher_specialization_frmid").prop("disabled", false);
                                                            $("#teacher_school_frmid").prop("disabled", false);
                                                            $("#teacher_surname_frmid").val("").trigger("change");
                                                            $("#teacher_name_frmid").val("").trigger("change");
                                                            $("#teacher_specialization_frmid").val("").trigger("change");
                                                            $("#teacher_school_frmid").val("").trigger("change");
                                                        }
                                                        else {
                                                            $("#teacher_surname_frmid").val(data.teacher_surname).trigger("change");                                                            
                                                            $("#teacher_name_frmid").val(data.teacher_name).trigger("change");
                                                            $("#teacher_specialization_frmid").val(data.specialisation_id).trigger("change");
                                                            $("#teacher_school_frmid").val(data.school_id).trigger("change");
                                                            $("#teacher_surname_frmid").prop("disabled", true);
                                                            $("#teacher_name_frmid").prop("disabled", true);
                                                            $("#teacher_specialization_frmid").prop("disabled", true);
                                                            //$("#teacher_school_frmid").prop("disabled", true);
                                                        }
                                                     },
                                            error: function(){alert("Hallo");}

                        	               })
                                }';

$this->registerJs($ajaxscript_searchTeacherById, View::POS_HEAD);

$url = Url::to('/disposal/disposal/getteacher-ajax');
?>
<?php $form = ActiveForm::begin(); ?>
<div class="disposal-form">
	<div class="row">
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_registrynumber')->textInput(['disabled' => $disabled, 'id' => 'teacher_regnumber_frmid', 'oninput' => 'searchTeacherById("' . $url .'");']) ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_surname')->textInput(['disabled' => $disabled, 'id' => 'teacher_surname_frmid']) ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_name')->textInput(['disabled' => $disabled, 'id' => 'teacher_name_frmid']) ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'specialisation_id')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map($specialisations, 'id', 'code'),
		                                        'options' => ['disabled' => $disabled, 'id' => 'teacher_specialization_frmid', 'placeholder' => Yii::t('app', 'Select specialisation...')],
                                            ])->label('Ειδικότητα'); ?>
        </div>		
	</div>
	<div class="row">
		<div class="col-lg-3">
			<?= $form->field($teacher_model, 'school_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
			                    'options' => ['id' => 'teacher_school_frmid', 'placeholder' => Yii::t('app', 'Select school...')],
                            ])->label('Σχολείο Υπηρέτησης'); ?>
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
