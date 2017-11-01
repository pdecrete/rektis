<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admapp\Util\Html as admappHtml;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Transport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-form">
    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>
    <!-- display error summary -->
	<?= $form->errorSummary($model); ?>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#application" aria-controls="application" role="tab" data-toggle="tab"><?= Yii::t('app', 'Application') ?></a></li>
      <li role="presentation"><a href="#approval" aria-controls="approval" role="tab" data-toggle="tab"><?= Yii::t('app', 'Approval') ?></a></li>
      <li role="presentation"><a href="#money" aria-controls="money" role="tab" data-toggle="tab"><?= Yii::t('app', 'Economic') ?></a></li>
    </ul>

	<div class="tab-content">
		<!-- Application Tab -->
		<div role="tabpanel" class="tab-pane fade-in active" id="application">
			<br>
			<?= $form->field($model, 'employee')->widget(Select2::classname(), [
					'data' => \app\models\Employee::find()->innerJoin('admapp_specialisation', 'admapp_specialisation.id=admapp_employee.specialisation')->select(["CONCAT(admapp_employee.surname, \" \", admapp_employee.name, \" του \", admapp_employee.fathersname,  \" (\", admapp_specialisation.code, \")\") as fname", "admapp_employee.id"])->orderBy("fname")->indexBy("id")->column(),
					'options' => [
						'placeholder' => Yii::t('app', 'Choose...'),
						'onchange' => '					
								$.post( "'.Url::to('employeedef?empid=').'"+$(this).val() , function( data ) {
									results = JSON.parse(data);
									$("#' . Html::getInputId($model, 'base')  . '").val(results.base);									
								});
							',			
						],					
				]);
			?>
			<?=
				$form->field($model, 'start_date')->widget(DateControl::classname(), [
					'type' => DateControl::FORMAT_DATE
				]);
			?>
			<?=
				$form->field($model, 'end_date')->widget(DateControl::classname(), [
					'type' => DateControl::FORMAT_DATE
				]);
			?>
			<div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
					<?= admappHtml::displayCopyFieldValueButton($model, 'start_date', 'end_date', 'Αντιγραφή από ' . $model->getAttributeLabel('start_date'), null, '-disp'); ?>
				</div>
			</div>
			<?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>
			<?=
				$form->field($model, 'from_to')->widget(Select2::classname(), [
					'data' => \app\models\TransportDistance::find()->select(['name', 'id'])->indexBy('id')->column(),
					'options' => [
						'placeholder' => Yii::t('app', 'Choose...'),
						'onchange' => '					
							$.post( "'.Url::to('calculate?routeid=').'"+$(this).val() + "&modeid=" + $("#' . Html::getInputId($model, 'mode')  . '").val() + "&days=" +$("#' . Html::getInputId($model, 'days_applied')  . '").val() + "&ticket=" +$("#' . Html::getInputId($model, 'ticket_value')  . '").val() + "&night_reimb=" +$("#' . Html::getInputId($model, 'night_reimb')  . '").val() + "&nights_out=" + $("#' . Html::getInputId($model, 'nights_out')  . '").val() , function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
								$("#' . Html::getInputId($model, 'klm_reimb')  . '").val(results.klm_reimb);
								$("#' . Html::getInputId($model, 'days_out')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'day_reimb')  . '").val(results.day_reimb);
								$("#' . Html::getInputId($model, 'reimbursement')  . '").val(results.reimbursement);
								$("#' . Html::getInputId($model, 'mtpy')  . '").val(results.mtpy);
								$("#' . Html::getInputId($model, 'pay_amount')  . '").val(results.pay_amount);
								$("#' . Html::getInputId($model, 'code719')  . '").val(results.code719);
								$("#' . Html::getInputId($model, 'code721')  . '").val(results.code721);
								$("#' . Html::getInputId($model, 'code722')  . '").val(results.code722);
								$("#' . Html::getInputId($model, 'nights_out')  . '").val(results.nights_out);
							});
						',
					],
				]);
/*
								alert("klm="+results.klm);
								alert("klm_reimb="+results.klm_reimb);
								alert("days_out="+results.days_out);
								alert("day_reimb="+results.day_reimb);
								alert("reimbursement="+results.reimbursement);
								alert("mtpy="+results.mtpy);
								alert("pay_amount="+results.pay_amount);				

//							$.post( "'.Url::to('routeinfo?routeid=').'"+$(this).val() + "&id='. $model->id .'" , function( data ) {
							$.post( "'.Url::to('routeinfo?routeid=').'"+$(this).val() , function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
							});
						',
					],
				]);
				
*/				
			?>
				
			<?= $form->field($model, 'base')->textInput(); ?>
		
			<?=
				$form->field($model, 'mode')->widget(Select2::classname(), [
					'data' => \app\models\TransportMode::find()->select(['name', 'id'])->indexBy('id')->column(),
					'options' => [
						'placeholder' => Yii::t('app', 'Choose...'),
						'onchange' => '					
							$.post( "'.Url::to('calculate?routeid=').'" + $("#' . Html::getInputId($model, 'from_to')  . '").val() + "&modeid=" + $(this).val() + "&days=" + $("#' . Html::getInputId($model, 'days_applied')  . '").val() + "&ticket=" +$("#' . Html::getInputId($model, 'ticket_value')  . '").val() + "&night_reimb=" +$("#' . Html::getInputId($model, 'night_reimb')  . '").val() + "&nights_out=" + $("#' . Html::getInputId($model, 'nights_out')  . '").val() , function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
								$("#' . Html::getInputId($model, 'klm_reimb')  . '").val(results.klm_reimb);
								$("#' . Html::getInputId($model, 'days_out')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'day_reimb')  . '").val(results.day_reimb);
								$("#' . Html::getInputId($model, 'reimbursement')  . '").val(results.reimbursement);
								$("#' . Html::getInputId($model, 'mtpy')  . '").val(results.mtpy);
								$("#' . Html::getInputId($model, 'pay_amount')  . '").val(results.pay_amount);
								$("#' . Html::getInputId($model, 'code719')  . '").val(results.code719);
								$("#' . Html::getInputId($model, 'code721')  . '").val(results.code721);
								$("#' . Html::getInputId($model, 'code722')  . '").val(results.code722);
								$("#' . Html::getInputId($model, 'nights_out')  . '").val(results.nights_out);
							});
						',
					],
				]);
			?>	
			<?= $form->field($model, 'days_applied')->widget(MaskedInput::classname(), [
					'name' => 'days_applied', 
					'mask' => '9{1,2}' ,
					'clientOptions' => [
						'alias' => 'integer',
					],
					'options' => [
						'onchange' => '					
							$.post( "'.Url::to('calculate?routeid=').'" + $("#' . Html::getInputId($model, 'from_to')  . '").val() + "&modeid=" + $("#' . Html::getInputId($model, 'mode')  . '").val()  + "&days=" + $(this).val() + "&ticket=" +$("#' . Html::getInputId($model, 'ticket_value')  . '").val() + "&night_reimb=" +$("#' . Html::getInputId($model, 'night_reimb')  . '").val() + "&nights_out=" + $("#' . Html::getInputId($model, 'nights_out')  . '").val() , function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
								$("#' . Html::getInputId($model, 'klm_reimb')  . '").val(results.klm_reimb);
								$("#' . Html::getInputId($model, 'days_out')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'day_reimb')  . '").val(results.day_reimb);
								$("#' . Html::getInputId($model, 'reimbursement')  . '").val(results.reimbursement);
								$("#' . Html::getInputId($model, 'mtpy')  . '").val(results.mtpy);
								$("#' . Html::getInputId($model, 'pay_amount')  . '").val(results.pay_amount);
								$("#' . Html::getInputId($model, 'code719')  . '").val(results.code719);
								$("#' . Html::getInputId($model, 'code721')  . '").val(results.code721);
								$("#' . Html::getInputId($model, 'code722')  . '").val(results.code722);
								$("#' . Html::getInputId($model, 'nights_out')  . '").val(results.nights_out);
							});
						',
					],
				]);
			?>
			
			<?= $form->field($model, 'ticket_value')->widget(MaskedInput::classname(), [
					'name' => 'ticket_value', 
//					'mask' => '9{1,4},99',
					'clientOptions' => [
						'alias' => 'decimal',
						'autoGroup' => true,
					],
					'options' => [
						'onchange' => '					
							$.post( "'.Url::to('calculate?routeid=').'" + $("#' . Html::getInputId($model, 'from_to')  . '").val() + "&modeid=" + $("#' . Html::getInputId($model, 'mode')  . '").val()  + "&days=" + $("#' . Html::getInputId($model, 'days_applied')  . '").val() + "&ticket=" + $(this).val() + "&night_reimb=" + $("#' . Html::getInputId($model, 'night_reimb')  . '").val() + "&nights_out=" + $("#' . Html::getInputId($model, 'nights_out')  . '").val() , function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
								$("#' . Html::getInputId($model, 'klm_reimb')  . '").val(results.klm_reimb);
								$("#' . Html::getInputId($model, 'days_out')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'day_reimb')  . '").val(results.day_reimb);
								$("#' . Html::getInputId($model, 'reimbursement')  . '").val(results.reimbursement);
								$("#' . Html::getInputId($model, 'mtpy')  . '").val(results.mtpy);
								$("#' . Html::getInputId($model, 'pay_amount')  . '").val(results.pay_amount);
								$("#' . Html::getInputId($model, 'code719')  . '").val(results.code719);
								$("#' . Html::getInputId($model, 'code721')  . '").val(results.code721);
								$("#' . Html::getInputId($model, 'code722')  . '").val(results.code722);
								$("#' . Html::getInputId($model, 'nights_out')  . '").val(results.nights_out);
							});
						',
					],
				]);
			?>
			<?= $form->field($model, 'night_reimb')->widget(MaskedInput::classname(), [
					'name' => 'night_reimb', 
					'clientOptions' => [
						'alias' => 'decimal',
						'autoGroup' => true,
					],
					'options' => [
						'onchange' => '					
							$.post( "'.Url::to('calculate?routeid=').'" + $("#' . Html::getInputId($model, 'from_to')  . '").val() + "&modeid=" + $("#' . Html::getInputId($model, 'mode')  . '").val()  + "&days=" + $("#' . Html::getInputId($model, 'days_applied')  . '").val() + "&ticket=" +$("#' . Html::getInputId($model, 'ticket_value')  . '").val() + "&night_reimb=" + $(this).val() 								+ "&nights_out=" + $("#' . Html::getInputId($model, 'nights_out')  . '").val() , function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
								$("#' . Html::getInputId($model, 'klm_reimb')  . '").val(results.klm_reimb);
								$("#' . Html::getInputId($model, 'days_out')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'day_reimb')  . '").val(results.day_reimb);
								$("#' . Html::getInputId($model, 'reimbursement')  . '").val(results.reimbursement);
								$("#' . Html::getInputId($model, 'mtpy')  . '").val(results.mtpy);
								$("#' . Html::getInputId($model, 'pay_amount')  . '").val(results.pay_amount);
								$("#' . Html::getInputId($model, 'code719')  . '").val(results.code719);
								$("#' . Html::getInputId($model, 'code721')  . '").val(results.code721);
								$("#' . Html::getInputId($model, 'code722')  . '").val(results.code722);
								$("#' . Html::getInputId($model, 'nights_out')  . '").val(results.nights_out);
							});
						',
					],
				]);
			?>

			<?= $form->field($model, 'accompanying_document')->textInput(['maxlength' => true]) ?>
		</div>

		<!-- Approval Tab -->
		<div role="tabpanel" class="tab-pane fade" id="approval">
			<br>
			<?=
				$form->field($model, 'type')->widget(Select2::classname(), [
					'data' => \app\models\TransportType::find()->select(['name', 'id'])->indexBy('id')->column(),
					'options' => ['placeholder' => Yii::t('app', 'Choose...')],
				]);
			?>
			<?= $form->field($model, 'count_flag')->checkbox($options = [], $enclosedByLabel = false ) ?>
			<?= $form->field($model, 'decision_protocol')->textInput() ?>
			<?=
				$form->field($model, 'decision_protocol_date')->widget(DateControl::classname(), [
					'type' => DateControl::FORMAT_DATE
				]);
			?>
			<?= $form->field($model, 'application_protocol')->textInput() ?>
			<?=
				$form->field($model, 'application_protocol_date')->widget(DateControl::classname(), [
					'type' => DateControl::FORMAT_DATE
				]);
			?>
			<div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
					<?= admappHtml::displayCopyFieldValueButton($model, 'decision_protocol_date', 'application_protocol_date', 'Αντιγραφή από ' . $model->getAttributeLabel('decision_protocol_date'), null, '-disp'); ?>
				</div>
			</div>
			<?=
				$form->field($model, 'application_date')->widget(DateControl::classname(), [
					'type' => DateControl::FORMAT_DATE
				]);
			?>
			<div class="form-group">
				<div class="col-lg-offset-2 col-lg-10">
					<?= admappHtml::displayCopyFieldValueButton($model, 'application_protocol_date', 'application_date', 'Αντιγραφή από ' . $model->getAttributeLabel('application_protocol_date'), null, '-disp'); ?>
				</div>
			</div> 	
			<?=
				$form->field($model, 'fund1')->widget(Select2::classname(), [
					'data' => \app\models\TransportFunds::find()->select(["CONCAT(kae, ' (', name, '/', date, ') - ', code )", 'id'])->orderBy('code', 'date DESC')->indexBy('id')->column(),
					'options' => ['placeholder' => Yii::t('app', 'Choose...')],
					'pluginOptions' => [
						  'allowClear' => true
					],
				]);
			?>
			<?=
				$form->field($model, 'fund2')->widget(Select2::classname(), [
					'data' => \app\models\TransportFunds::find()->select(["CONCAT(kae, ' (', name, '/', date, ') - ', code )", 'id'])->orderBy('code', 'date DESC')->indexBy('id')->column(),
					'options' => ['placeholder' => Yii::t('app', 'Choose...')],
					'pluginOptions' => [
						  'allowClear' => true
					],
				]);
			?>
			<?=
				$form->field($model, 'fund3')->widget(Select2::classname(), [
					'data' => \app\models\TransportFunds::find()->select(["CONCAT(kae, ' (', name, '/', date, ') - ', code )", 'id'])->orderBy('code', 'date DESC')->indexBy('id')->column(),
					'options' => ['placeholder' => Yii::t('app', 'Choose...')],
					'pluginOptions' => [
						  'allowClear' => true
					],
				]);
			?>	
			<?= $form->field($model, 'extra_reason')->textArea(['maxlength' => true]) ?>
		</div>
			
		<!-- Money Tab -->
		<div role="tabpanel" class="tab-pane fade" id="money">	
			<br>			
			<?= $form->field($model, 'klm')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'klm_reimb')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'days_out')->widget(MaskedInput::classname(), [
					'name' => 'days_out', 
					'mask' => '9{1,2}' ,
					'options' => [
						'onchange' => '					
							$.post( "'.Url::to('calculate?routeid=').'" + $("#' . Html::getInputId($model, 'from_to')  . '").val() + "&modeid=" + $("#' . Html::getInputId($model, 'mode')  . '").val()  + "&days=" + $(this).val() + "&ticket=" +$("#' . Html::getInputId($model, 'ticket_value')  . '").val()  + "&night_reimb=" + $("#' . Html::getInputId($model, 'night_reimb')  . '").val() + "&nights_out=" + $("#' . Html::getInputId($model, 'nights_out')  . '").val() , function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
								$("#' . Html::getInputId($model, 'klm_reimb')  . '").val(results.klm_reimb);
								$("#' . Html::getInputId($model, 'days_applied')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'days_out')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'day_reimb')  . '").val(results.day_reimb);
								$("#' . Html::getInputId($model, 'reimbursement')  . '").val(results.reimbursement);
								$("#' . Html::getInputId($model, 'mtpy')  . '").val(results.mtpy);
								$("#' . Html::getInputId($model, 'pay_amount')  . '").val(results.pay_amount);
								$("#' . Html::getInputId($model, 'code719')  . '").val(results.code719);
								$("#' . Html::getInputId($model, 'code721')  . '").val(results.code721);
								$("#' . Html::getInputId($model, 'code722')  . '").val(results.code722);
								$("#' . Html::getInputId($model, 'nights_out')  . '").val(results.nights_out);							
							});
						',
					],
				]);
			?>
			<?= $form->field($model, 'day_reimb')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'nights_out')->widget(MaskedInput::classname(), [
					'name' => 'nights_out', 
					'mask' => '9{1,2}' ,
					'options' => [
						'onchange' => '					
							$.post( "'.Url::to('calculate?routeid=').'" + $("#' . Html::getInputId($model, 'from_to')  . '").val() + "&modeid=" + $("#' . Html::getInputId($model, 'mode')  . '").val()  + "&days=" +$("#' . Html::getInputId($model, 'days_applied')  . '").val() + "&ticket=" +$("#' . Html::getInputId($model, 'ticket_value')  . '").val()  + "&night_reimb=" + $("#' . Html::getInputId($model, 'night_reimb')  . '").val() + "&nights_out=" + $(this).val(), function( data ) {
								results = JSON.parse(data);
								$("#' . Html::getInputId($model, 'klm')  . '").val(results.klm);
								$("#' . Html::getInputId($model, 'klm_reimb')  . '").val(results.klm_reimb);
								$("#' . Html::getInputId($model, 'days_applied')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'days_out')  . '").val(results.days_out);
								$("#' . Html::getInputId($model, 'day_reimb')  . '").val(results.day_reimb);
								$("#' . Html::getInputId($model, 'reimbursement')  . '").val(results.reimbursement);
								$("#' . Html::getInputId($model, 'mtpy')  . '").val(results.mtpy);
								$("#' . Html::getInputId($model, 'pay_amount')  . '").val(results.pay_amount);
								$("#' . Html::getInputId($model, 'code719')  . '").val(results.code719);
								$("#' . Html::getInputId($model, 'code721')  . '").val(results.code721);
								$("#' . Html::getInputId($model, 'code722')  . '").val(results.code722);
								$("#' . Html::getInputId($model, 'nights_out')  . '").val(results.nights_out);
							});
						',
					],
				]);
			?>
			<?= $form->field($model, 'reimbursement')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'mtpy')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'pay_amount')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'code719')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'code721')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'code722')->textInput(['readonly' => true]) ?>
			<?= $form->field($model, 'expense_details')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
		</div>
		
	</div>
		
	<div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    
    
    <?php ActiveForm::end(); ?>
  


</div>
