<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admapp\Util\Html as admappHtml;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveBalance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-balance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'employee')->widget(Select2::classname(), [
        'data' => \app\models\Employee::find()->select(["CONCAT(surname, ' ', name, ' τ.', fathersname) as fname", 'id'])->orderBy('fname')->indexBy('id')->column(),
        'options' => [
			'placeholder' => Yii::t('app', 'Choose...'),
			'onchange' => '					
				$.post( "'.Url::to('leaveleft?empid=').'" + $(this).val() + "&leavetype=" + $("#' . Html::getInputId($model, 'leave_type')  . '").val() + "&year=" + $("#' . Html::getInputId($model, 'year')  . '").val()  + "&days=" +$("#' . Html::getInputId($model, 'days')  . '").val() , function( data ) {
					results = JSON.parse(data);
					$("#' . Html::getInputId($model, 'days')  . '").val(results.days);
				});
			',
		],		
    ]);
    ?>

    <?=
    $form->field($model, 'leave_type')->widget(Select2::classname(), [
        'data' => \app\models\LeaveType::find()->select(['name', 'id'])->indexBy('id')->column(),
        'options' => [
			'placeholder' => Yii::t('app', 'Choose...'),
			'onchange' => '					
				$.post( "'.Url::to('leaveleft?empid=').'" + $("#' . Html::getInputId($model, 'employee')  . '").val() + "&leavetype=" + $(this).val() + "&year=" + $("#' . Html::getInputId($model, 'year')  . '").val()  + "&days=" +$("#' . Html::getInputId($model, 'days')  . '").val() , function( data ) {
					results = JSON.parse(data);
					$("#' . Html::getInputId($model, 'days')  . '").val(results.days);
				});
			',
		],		       
    ]);
    ?>

	<?= $form->field($model, 'year')->widget(MaskedInput::classname(), [
			'name' => 'year', 
			'mask' => '2999',
			'options' => [
				'onchange' => '				
					$.post( "'.Url::to('leaveleft?empid=').'" + $("#' . Html::getInputId($model, 'employee')  . '").val() + "&leavetype=" + $("#' . Html::getInputId($model, 'leave_type')  . '").val()  + "&year=" + $(this).val() + "&days=" +$("#' . Html::getInputId($model, 'days')  . '").val() , function( data ) {
						results = JSON.parse(data);
						$("#' . Html::getInputId($model, 'days')  . '").val(results.days);
					});
				',
			],		
		]);
	?>     

    <?= $form->field($model, 'days')->widget(MaskedInput::classname(), ['name' => 'days', 'mask' => '9{1,2}']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
