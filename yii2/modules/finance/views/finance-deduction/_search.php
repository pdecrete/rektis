<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceDeductionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-deduction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'deduct_id') ?>

    <?= $form->field($model, 'deduct_name') ?>

    <?= $form->field($model, 'deduct_description') ?>

    <?= $form->field($model, 'deduct_date') ?>

    <?= $form->field($model, 'deduct_percentage') ?>

    <?php // echo $form->field($model, 'deduct_downlimit') ?>

    <?php // echo $form->field($model, 'deduct_uplimit') ?>

    <?php // echo $form->field($model, 'deduct_obsolete') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
