<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'inv_id') ?>

    <?= $form->field($model, 'inv_number') ?>

    <?= $form->field($model, 'inv_date') ?>

    <?= $form->field($model, 'inv_order') ?>

    <?= $form->field($model, 'inv_deleted') ?>

    <?php // echo $form->field($model, 'suppl_id') ?>

    <?php // echo $form->field($model, 'exp_id') ?>

    <?php // echo $form->field($model, 'invtype_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
