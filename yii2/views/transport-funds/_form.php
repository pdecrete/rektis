<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\TransportFunds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-funds-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE
    ]);
    ?>

	<?= $form->field($model, 'year')->widget(MaskedInput::classname(), [
			'name' => 'year', 
			'mask' => '2999'
		]);
	?>     

    <?= $form->field($model, 'ada')->textInput(['maxlength' => true]) ?>

	<?=
		$form->field($model, 'service')->widget(Select2::classname(), [
			'data' => \app\models\Service::find()->select(['name', 'id'])->indexBy('id')->column(),
			'options' => ['placeholder' => 'Επιλέξτε...'],
		]);
	?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'kae')->widget(Select2::classname(), [
		'data' => \app\models\TransportFunds::kaeList(),
		'options' => ['placeholder' => Yii::t('app', 'Choose...')],
			'pluginOptions' => [
			'allowClear' => true
			],
        ]);
	?>

	<?= $form->field($model, 'amount')->widget(MaskedInput::classname(), [
			'name' => 'amount', 
			'clientOptions' => [
				'alias' => 'decimal',
				'autoGroup' => true,
			],
		]);
	?>     

    <?= $form->field($model, 'count_flag')->checkbox($options = [], $enclosedByLabel = false ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
