<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\StteacherMkexperienceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stteacher-mkexperience-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'teacher_id') ?>

    <?= $form->field($model, 'exp_startdate') ?>

    <?= $form->field($model, 'exp_enddate') ?>

    <?= $form->field($model, 'exp_years') ?>

    <?= $form->field($model, 'exp_months') ?>

    <?= $form->field($model, 'exp_days') ?>

    <?= $form->field($model, 'exp_sectorname')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'exp_sectortype') ?>

    <?= $form->field($model, 'exp_info') ?>

    <?= $form->field($model, 'exp_mkvalid') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
