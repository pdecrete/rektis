<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Position */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operation_id')->textInput() ?>

    <?= $form->field($model, 'specialisation_id')->textInput() ?>

    <?= $form->field($model, 'teachers_count')->textInput() ?>

    <?= $form->field($model, 'hours_count')->textInput() ?>

    <?= $form->field($model, 'whole_teacher_hours')->textInput() ?>

    <?= $form->field($model, 'covered_teachers_count')->textInput() ?>

    <?= $form->field($model, 'covered_hours_count')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
