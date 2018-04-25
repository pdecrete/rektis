<?php

use app\modules\schooltransport\Module;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */
/* @var $form yii\widgets\ActiveForm */

//echo "<pre>"; echo $programcateg_id; echo "</pre>"; die();
if($program_model['programcategory_id'] == 11){
    $program_model['program_title'] = 'Βουλή των Ελλήνων';
    $program_model['program_code'] = '-';
    $meeting_model['meeting_country'] = 'Ελλάδα';
    $meeting_model['meeting_city'] = 'Αθήνα';
}
    
?>

<div class="schtransport-transport-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'school_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
            'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select school...'), 'disabled' => $disabled],
        ])->label('Σχολείο'); ?>
  

	<?php  if(empty($typeahead_data['PROGRAMCODES'])): 
	           echo $form->field($program_model, 'program_code')->textInput(['maxlength' => true, 'disabled' => $disabled]);
	       else:
	           echo $form->field($program_model, 'program_code')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
	                                                        'dataset' => [['local' => $typeahead_data['PROGRAMCODES'], 'limit' => 10]],
	                                                        'disabled' => $disabled
                                                       ]);
	       endif;
    ?>
	                                                       
	<?php  if(empty($typeahead_data['PROGRAMTITLES'])): 
	           echo $form->field($program_model, 'program_title')->textInput(['maxlength' => true, 'disabled' => $disabled]);
	       else:
	           echo $form->field($program_model, 'program_title')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
	                                                         'dataset' => [['local' => $typeahead_data['PROGRAMTITLES'], 'limit' => 10]], 
	                                                         'disabled' => $disabled
	                                                       ]); 
           endif;
    ?>

	<?php  if(empty($typeahead_data['COUNTRIES'])): 
	           echo $form->field($meeting_model, 'meeting_country')->textInput(['maxlength' => true, 'disabled' => $disabled]);
	       else:
	           echo $form->field($meeting_model, 'meeting_country')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
                                                            'dataset' => [['local' => $typeahead_data['COUNTRIES'], 'limit' => 10]],
                                                            'disabled' => $disabled
	                                                       ]); 
           endif;
    ?>      
        
	<?php  if(empty($typeahead_data['CITIES'])): 
	           echo $form->field($meeting_model, 'meeting_city')->textInput(['maxlength' => true, 'disabled' => $disabled]);
	       else:
	           echo $form->field($meeting_model, 'meeting_city')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
	                                                        'dataset' => [['local' => $typeahead_data['CITIES'], 'limit' => 10]],
	                                                        'disabled' => $disabled
	                                                       ]);            
           endif;
    ?>    

	<!--	           
    <?= $form->field($meeting_model, 'meeting_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE, 'disabled' => $disabled]); ?>
    <?= $form->field($meeting_model, 'meeting_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE, 'disabled' => $disabled]); ?>
	-->
    
    <?php ;//$form->field($model, 'transport_submissiondate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    <?= $form->field($model, 'transport_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE, 'disabled' => $disabled]); ?>

    <?= $form->field($model, 'transport_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE, 'disabled' => $disabled]); ?>

	<?php  if(in_array($programcateg_id, [6, 7, 8, 9, 10, 11]))
               echo $form->field($model, 'transport_headteacher')->textInput(['maxlength' => true, 'disabled' => $disabled]);
	?>

    <?= $form->field($model, 'transport_teachers')->textarea(['rows' => '6', 'disabled' => $disabled]) ?>

	<?php  if(in_array($programcateg_id, [6, 7, 8, 9, 10, 11]))
	           echo $form->field($model, 'transport_class')->textInput(['maxlength' => true, 'disabled' => $disabled]);
	?>

	<?php  if($programcateg_id != 4)
	           echo $form->field($model, 'transport_students')->textarea(['rows' => '6', 'disabled' => $disabled]);
	?>	
   
   	<?php  if(in_array($programcateg_id, [6, 7, 8, 9, 10, 11]))
   	           echo $form->field($model, 'transport_schoolrecord')->textInput(['maxlength' => true, 'disabled' => $disabled]);
	?>
   
	<?= $form->field($model, 'transport_localdirectorate_protocol')->textInput(['maxlength' => true, 'disabled' => $disabled]) ?>
   
	<?= $form->field($model, 'transport_pde_protocol')->textInput(['maxlength' => true, 'disabled' => $disabled]) ?>
		
    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>    	
        <?php   if(!$disabled): 
                    echo Html::submitButton($model->isNewRecord ? Module::t('modules/schooltransport/app', 'Create') : Module::t('modules/schooltransport/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); 
                else:
                    echo Html::a(Module::t('modules/schooltransport/app', 'Update'), ['update', 'id' => $model->transport_id], ['class' => 'btn btn-primary']);
                endif;
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
