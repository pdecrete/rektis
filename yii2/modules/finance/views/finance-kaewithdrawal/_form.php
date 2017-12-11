<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaewithdrawal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-kaewithdrawal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kaewithdr_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kaewithdr_decision')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kaewithdr_date')->textInput() ?>

    <?= $form->field($model, 'kaecredit_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
