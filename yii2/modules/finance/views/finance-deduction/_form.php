<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceDeduction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-deduction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deduct_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deduct_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deduct_date')->textInput() ?>

    <?= $form->field($model, 'deduct_percentage')->textInput() ?>

    <?= $form->field($model, 'deduct_downlimit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deduct_uplimit')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
