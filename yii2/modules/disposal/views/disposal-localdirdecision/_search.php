<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecisionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disposal-localdirdecision-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'localdirdecision_id') ?>

    <?= $form->field($model, 'localdirdecision_protocol') ?>

    <?= $form->field($model, 'localdirdecision_subject') ?>

    <?= $form->field($model, 'localdirdecision_action') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <?php // echo $form->field($model, 'deleted')?>

    <?php // echo $form->field($model, 'archived')?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app/modules/disposal/', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app/modules/disposal/', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
