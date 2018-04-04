<?php

use app\modules\schooltransport\Module;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */
/* @var $form yii\widgets\ActiveForm */

//echo "<pre>"; print_r($typeahead_data); echo "</pre>"; die();
?>

<div class="schtransport-transport-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'school_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
            'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select school...')],
        ])->label('Σχολείο'); ?>
  
	<?php  if(empty($typeahead_data['PROGRAMCODES'])): 
	           echo $form->field($program_model, 'program_code')->textInput(['maxlength' => true]);
	       else:
	           echo $form->field($program_model, 'program_code')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
	                                                        'dataset' => [['local' => $typeahead_data['PROGRAMCODES'], 'limit' => 10]]
                                                       ]);
	       endif;
    ?>
	                                                       
	<?php  if(empty($typeahead_data['PROGRAMTITLES'])): 
	           echo $form->field($program_model, 'program_title')->textInput(['maxlength' => true]);
	       else:
	           echo $form->field($program_model, 'program_title')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
	                                                        'dataset' => [['local' => $typeahead_data['PROGRAMTITLES'], 'limit' => 10]]
	                                                       ]); 
           endif;
    ?>

	<?php  if(empty($typeahead_data['COUNTRIES'])): 
	           echo $form->field($meeting_model, 'meeting_country')->textInput(['maxlength' => true]);
	       else:
	           echo $form->field($meeting_model, 'meeting_country')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
	                                                        'dataset' => [['local' => $typeahead_data['COUNTRIES'], 'limit' => 10]]
	                                                       ]); 
           endif;
    ?>      
        
	<?php  if(empty($typeahead_data['CITIES'])): 
	           echo $form->field($meeting_model, 'meeting_city')->textInput(['maxlength' => true]);
	       else:
	           echo $form->field($meeting_model, 'meeting_city')->widget(Typeahead::classname(), 
	                                                       ['pluginOptions' => ['highlight'=>true],
	                                                        'dataset' => [['local' => $typeahead_data['CITIES'], 'limit' => 10]]
	                                                       ]);            
           endif;
    ?>    
	           
    <?= $form->field($meeting_model, 'meeting_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>
    <?= $form->field($meeting_model, 'meeting_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    
    <?php ;//$form->field($model, 'transport_submissiondate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    <?= $form->field($model, 'transport_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    <?= $form->field($model, 'transport_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    <?= $form->field($model, 'transport_teachers')->textarea(['rows' => '6']) ?>

	<?= $form->field($model, 'transport_students')->textarea(['rows' => '6']) ?>
   
	<?= $form->field($model, 'transport_localdirectorate_protocol')->textInput(['maxlength' => true]) ?>
   
	<?= $form->field($model, 'transport_pde_protocol')->textInput(['maxlength' => true]) ?>
		
    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
