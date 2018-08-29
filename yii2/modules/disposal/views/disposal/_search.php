<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disposal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'disposal_id') ?>

    <?= $form->field($model, 'disposal_startdate') ?>

    <?= $form->field($model, 'disposal_enddate') ?>

    <?= $form->field($model, 'disposal_hours') ?>

    <?= $form->field($model, 'teacher_id') ?>

    <?php // echo $form->field($model, 'school_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
