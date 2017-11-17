<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\SubstituteTeacherFile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="substitute-teacher-file-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'original_filename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'filename')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-sm-4"><strong><?= $model->getAttributeLabel('created_at') ?></strong></div>
        <div class="col-sm-4"><div class="form-control form-control-static"><?= $model->created_at ?></div></div>
    </div>
    <div class="row">
        <div class="col-sm-4"><strong><?= $model->getAttributeLabel('updated_at') ?></strong></div>
        <div class="col-sm-4"><div class="form-control form-control-static"><?= $model->updated_at ?></div></div>
    </div>
    <div class="row">
        <div class="col-sm-4"><strong><?= $model->getAttributeLabel('deleted') ?></strong></div>
        <div class="col-sm-4"><div class="form-control form-control-static"><?= $model->deleted_str ?></div></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
