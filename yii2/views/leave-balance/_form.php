<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveBalance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-balance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'employee')->widget(Select2::classname(), [
        'data' => \app\models\Employee::find()->select(["CONCAT(surname, ' ', name) as fname", 'id'])->orderBy('fname')->indexBy('id')->column(),
        'options' => ['placeholder' => 'Επιλέξτε...'],
    ]);
    ?>

    <?=
    $form->field($model, 'leave_type')->widget(Select2::classname(), [
        'data' => \app\models\LeaveType::find()->select(['name', 'id'])->indexBy('id')->column(),
        'options' => ['placeholder' => 'Επιλέξτε...'],
    ]);
    ?>

	<?= $form->field($model, 'year')->widget(MaskedInput::classname(), [
			'name' => 'year', 
			'mask' => '2999'
		]);
	?>     

    <?= $form->field($model, 'days')->widget(MaskedInput::classname(), ['name' => 'days', 'mask' => '9{1,2}']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
