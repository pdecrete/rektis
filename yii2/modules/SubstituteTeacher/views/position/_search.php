<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\PositionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'operation_id') ?>

    <?= $form->field($model, 'specialisation_id') ?>

    <?= $form->field($model, 'prefecture_id') ?>

    <?php // echo $form->field($model, 'teachers_count') ?>

    <?php // echo $form->field($model, 'hours_count') ?>

    <?php // echo $form->field($model, 'whole_teacher_hours') ?>

    <?php // echo $form->field($model, 'covered_teachers_count') ?>

    <?php // echo $form->field($model, 'covered_hours_count') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
