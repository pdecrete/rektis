<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecision */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disposal-localdirdecision-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'localdirdecision_protocol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'localdirdecision_subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'localdirdecision_action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'archived')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app/modules/disposal/', 'Create') : Yii::t('app/modules/disposal/', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
