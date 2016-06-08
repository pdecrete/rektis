<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Leave */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'employee')->textInput() ?>
    <?= $form->field($model, 'type')->textInput() ?>
    <?= $form->field($model, 'decision_protocol')->textInput() ?>
    <?= $form->field($model, 'decision_protocol_date')->textInput() ?>
    <?= $form->field($model, 'application_protocol')->textInput() ?>
    <?= $form->field($model, 'application_protocol_date')->textInput() ?>
    <?= $form->field($model, 'application_date')->textInput() ?>
    <?= $form->field($model, 'accompanying_document')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'duration')->textInput() ?>
    <?= $form->field($model, 'start_date')->textInput() ?>
    <?= $form->field($model, 'end_date')->textInput() ?>
    <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?php if (!$model->isNewRecord) : ?>
        <?= admappHtml::displayValueOfField($model, 'create_ts', 2, 6) ?>
        <?= admappHtml::displayValueOfField($model, 'update_ts', 2, 6) ?>
    <?php endif; ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Ενημέρωση στοιχείων', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Επιστροφή', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
