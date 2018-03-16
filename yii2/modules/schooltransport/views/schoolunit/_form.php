<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\Schoolunit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schoolunit-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'school_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'directorate_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
