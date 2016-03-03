<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>

    <? // $form->field($model, 'status')->textInput() ?>
    <?=
    $form->field($model, 'status')->dropdownList(
            \app\models\EmployeeStatus::find()->select(['name', 'id'])->indexBy('id')->column(), ['prompt' => 'Select status']
    );
    ?>
    <pre>
    <?php
        \yii\helpers\VarDumper::dump(\app\models\EmployeeStatus::find()->select(['name', 'id'])->indexBy('id')->column());
        \yii\helpers\VarDumper::dump(\app\models\EmployeeStatus::find()->select(['name', 'id'])->column());
    ?>
    </pre>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fathersname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mothersname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tax_identification_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identity_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'social_security_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'specialisation')->textInput() ?>

    <?= $form->field($model, 'identification_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appointment_fek')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appointment_date')->textInput() ?>

    <?= $form->field($model, 'service_organic')->textInput() ?>

    <?= $form->field($model, 'service_serve')->textInput() ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'rank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rank_date')->textInput() ?>

    <?= $form->field($model, 'pay_scale')->textInput() ?>

    <?= $form->field($model, 'pay_scale_date')->textInput() ?>

    <?= $form->field($model, 'service_adoption')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'service_adoption_date')->textInput() ?>

    <?= $form->field($model, 'master_degree')->textInput() ?>

    <?= $form->field($model, 'doctorate_degree')->textInput() ?>

    <?= $form->field($model, 'work_experience')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
