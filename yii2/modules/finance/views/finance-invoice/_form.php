<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inv_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inv_date')->textInput() ?>

    <?= $form->field($model, 'inv_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inv_deleted')->textInput() ?>

    <?= $form->field($model, 'suppl_id')->textInput() ?>

    <?= $form->field($model, 'exp_id')->textInput() ?>

    <?= $form->field($model, 'invtype_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
