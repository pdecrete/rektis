<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransportTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php // echo $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'create_ts') ?>

    <?= $form->field($model, 'update_ts') ?>

    <?= $form->field($model, 'deleted') ?>

    <?= $form->field($model, 'templatefilename1') ?>

    <?= $form->field($model, 'templatefilename2') ?>

\    <?= $form->field($model, 'templatefilename3') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
