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
//echo "<pre>"; print_r($directorates); echo "</pre>"; die();


$ajaxscript_searchTeacherById = 'function searchLocaldirdecisionById(url){
                                    var localdirdecision_protocol = document.getElementById("localdirdecision_protocol_frmid").value;
                                    var localdirdecision_directorate = document.getElementById("localdirdecision_directorate_frmid").value;


                                    $.ajax({ 
                                            url: url, 
                                            type: "post", 
                                            data: {"localdirdecision_protocol":localdirdecision_protocol, "localdirdecision_directorate":localdirdecision_directorate},
                                            success: function(data){
                                                        data = JSON.parse(data);
                                                        if(data == null) {
                                                            $("#localdirdecision_action_frmid").prop("disabled", false);
                                                            $("#localdirdecision_subject_frmid").prop("disabled", false);
                                                            $("#localdirdecision_action_frmid").val("").trigger("change");
                                                            $("#localdirdecision_subject_frmid").val("").trigger("change");
                                                        }
                                                        else {
                                                            $("#localdirdecision_subject_frmid").val(data.localdirdecision_subject).trigger("change");                                                            
                                                            $("#localdirdecision_action_frmid").val(data.localdirdecision_action).trigger("change");
                                                            $("#localdirdecision_action_frmid").prop("disabled", true);
                                                            $("#localdirdecision_subject_frmid").prop("disabled", true);
                                                        }
                                                     },
                                            error: function(){alert("Error");}

                        	               })
                                 }

                                 function searchTeacherById(url, value, idType){//alert(idType);
                                    var id = value;
                                    $.ajax({ 
                                            url: url, 
                                            type: "post", 
                                            data: {"id":id, "idType":idType},
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
                                                            if(idType === "regnumber")
                                                                $("#teacher_afm_frmid").val("").trigger("change");
                                                            else if(idType === "vat")
                                                                $("#teacher_regnumber_frmid").val("").trigger("change");
                                                        }
                                                        else {
                                                            $("#teacher_surname_frmid").val(data.teacher_surname).trigger("change");                                                            
                                                            $("#teacher_name_frmid").val(data.teacher_name).trigger("change");
                                                            $("#teacher_specialization_frmid").val(data.specialisation_id).trigger("change");
                                                            $("#teacher_school_frmid").val(data.school_id).trigger("change");
                                                            $("#teacher_surname_frmid").prop("disabled", true);
                                                            $("#teacher_name_frmid").prop("disabled", true);
                                                            $("#teacher_specialization_frmid").prop("disabled", true);
                                                            $("#teacher_school_frmid").prop("disabled", true);
                                                            $("#teacher_afm_frmid").val(data.teacher_afm).trigger("change");
                                                            $("#teacher_regnumber_frmid").val(data.teacher_registrynumber).trigger("change");
                                                        }
                                                     },
                                            error: function(){alert("Error");}

                        	               })
                                }';

$setenddate = 'function setEndOfTeachingYearDate() {                    
                    var enddatefrm = document.getElementById("disposalend_datepicker-disp");
                    var enddateflag = document.getElementById("chkbox_endteachyear");
                    enddatefrm.disabled = (enddateflag.checked == true);
                    //enddatefrm.disabled = !(enddateflag.checked == true)
                    if(enddatefrm.disabled == true)
                        $("#disposalend_datepicker-disp").kvDatepicker("update", "")
                }';

$this->registerJs($ajaxscript_searchTeacherById, View::POS_HEAD);
$this->registerJs($setenddate, View::POS_HEAD);

/*
$script = '$(document).on("click", "#chkbox_endteachyear", function enabledisableDisposalendDatepicker() {
                //var disposalend_datepicker = document.getElementById("disposalend_datepicker");
                var disposalend_datepicker = $("#disposalend_datepicker").prop("disabled", true);
                alert($("#disposalend_datepicker").kvDatepicker().attr("disabled"));
                if(disposalend_datepicker.disabled){
                    $("#disposalend_datepicker").kvDatepicker().prop("disabled", false);
                    alert("Done false...");
                }
                else {
                    $("#disposalend_datepicker").prop("disabled", true);
                    $("#disposalend_datepicker").val("").trigger("change");
                    alert("Done true...");
                }    
           })';
$this->registerJs($script, View::POS_END);
*/

$urlTeacherCheck = Url::to('/disposal/disposal/getteacher-ajax');
$urlLocaldirDecisionCheck = Url::to('/disposal/disposal/getlocaldirdecision-ajax');
                                                       
?>
<?php $form = ActiveForm::begin(); ?>
<div class="disposal-form">
	<div class="row">
		<div class="col-lg-6">
			<?= $form->field($localdirdecision_model, 'directorate_id')->widget(Select2::classname(), [
	                     'data' => ArrayHelper::map($directorates, 'directorate_id', 'directorate_name'), 
			             'options' => ['disabled' => $ldrdec_disabled, 'onchange' => 'searchLocaldirdecisionById("' . $urlLocaldirDecisionCheck .'");', 'id' => 'localdirdecision_directorate_frmid', 'placeholder' => DisposalModule::t('modules/disposal/app', 'Select Directorate ...')],			             
                    ])->label('Διεύθυνση Εκπαίδευσης'); ?>
		</div>	
		<div class="col-lg-3"><?= $form->field($localdirdecision_model, 'localdirdecision_protocol')->textInput(['disabled' => $ldrdec_disabled, 'id' => 'localdirdecision_protocol_frmid', 'oninput' => 'searchLocaldirdecisionById("' . $urlLocaldirDecisionCheck .'");']) ?></div>
		<div class="col-lg-3"><?= $form->field($localdirdecision_model, 'localdirdecision_action')->textInput(['disabled' => $ldrdec_disabled, 'id' => 'localdirdecision_action_frmid']) ?></div>		
	</div>
	<div class="row">
		<div class="col-lg-12"><?= $form->field($localdirdecision_model, 'localdirdecision_subject')->textInput(['disabled' => $ldrdec_disabled, 'id' => 'localdirdecision_subject_frmid']) ?></div>
	</div>
	<hr />
	<div class="row">
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_registrynumber')->textInput(['disabled' => $teacher_disabled, 'id' => 'teacher_regnumber_frmid', 'oninput' => 'searchTeacherById("' . $urlTeacherCheck .'", this.value, "regnumber");']) ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_afm')->textInput(['disabled' => $teacher_disabled, 'id' => 'teacher_afm_frmid', 'oninput' => 'searchTeacherById("' . $urlTeacherCheck .'", this.value, "vat");']) ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_surname')->textInput(['disabled' => $teacher_disabled, 'id' => 'teacher_surname_frmid']) ?></div>
		<div class="col-lg-3"><?= $form->field($teacher_model, 'teacher_name')->textInput(['disabled' => $teacher_disabled, 'id' => 'teacher_name_frmid']) ?></div>
		
	</div>
	<div class="row">
		<div class="col-lg-3"><?= $form->field($teacher_model, 'specialisation_id')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map($specialisations, 'id', 'code'),
		                                        'options' => ['disabled' => $teacher_disabled, 'id' => 'teacher_specialization_frmid', 'placeholder' => Yii::t('app', 'Select specialisation...')],
                                            ])->label('Ειδικότητα'); ?>
		</div>
		<div class="col-lg-3">
			<?= $form->field($teacher_model, 'school_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
			                    'options' => ['disabled' => $teacher_disabled, 'id' => 'teacher_school_frmid', 'placeholder' => Yii::t('app', 'Select school...')],
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
			<?= $form->field($model, 'disposal_endofteachingyear_flag')->checkbox(['label'=>'Λήξη στο τέλος του διδακτικού έτους', 'id' => 'chkbox_endteachyear', 'onclick' => 'setEndOfTeachingYearDate()']); ?>
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
                            ])->label('Καθήκον Διάθεσης'); ?>
		</div>			
	</div>	
    <div class="form-group pull-right">
        <?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

</div>
<?php ActiveForm::end(); ?>
