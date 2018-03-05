<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-expenditure-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'exp_id') ?>

    <?= $form->field($model, 'exp_amount') ?>

    <?= $form->field($model, 'exp_date') ?>

    <?= $form->field($model, 'exp_lock') ?>

    <?= $form->field($model, 'exp_deleted') ?>

    <?php // echo $form->field($model, 'suppl_id')?>

    <?php  //echo $form->field($model, 'fpa_value')?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
