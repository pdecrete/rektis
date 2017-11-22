<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-kaecredit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kaecredit_id') ?>

    <?= $form->field($model, 'kaecredit_amount') ?>

    <?= $form->field($model, 'kaecredit_date') ?>

    <?= $form->field($model, 'kaecredit_updated') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'kae_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
