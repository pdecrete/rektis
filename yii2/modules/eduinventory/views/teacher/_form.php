<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Teacher */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'teacher_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'teacher_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'teacher_registrynumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'specialisation_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($specialisations, 'id', 'code'),
            'options' => ['placeholder' => Yii::t('app', 'Select specialisation...')],
        ])->label('Ειδικότητα'); ?>

    <?= $form->field($model, 'school_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($schools, 'school_id', 'school_name'),
            'options' => ['placeholder' => Yii::t('app', 'Select school...')],
        ])->label('Σχολείο Υπηρέτησης'); ?>

    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
