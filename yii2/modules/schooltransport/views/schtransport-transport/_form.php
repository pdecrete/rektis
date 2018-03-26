<?php

use app\modules\schooltransport\Module;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($model); echo "</pre>";die();
//echo "I am here"; die();
?>

<div class="schtransport-transport-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'school_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
            'options' => ['placeholder' => Module::t('modules/schooltransport/app', 'Select school...')],
        ])->label('Σχολείο'); ?>
        
    <?= $form->field($program_model, 'program_code')->textInput()  ; ?>    
    <?= $form->field($program_model, 'program_title')->textInput()  ; ?>
        
    <?= $form->field($meeting_model, 'meeting_city')->textInput(['maxlength' => true]) ?>
	<?= $form->field($meeting_model, 'meeting_country')->textInput(['maxlength' => true]) ?>
    <?= $form->field($meeting_model, 'meeting_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>
    <?= $form->field($meeting_model, 'meeting_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    
    <?php ;//$form->field($model, 'transport_submissiondate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    <?= $form->field($model, 'transport_startdate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    <?= $form->field($model, 'transport_enddate')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE]); ?>

    <?= $form->field($model, 'transport_teachers')->textarea(['rows' => '6']) ?>

    <?= $form->field($model, 'transport_students')->textarea(['rows' => '6']) ?>
   
   

    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
