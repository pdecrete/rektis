<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schtransport-transport-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transport_submissiondate')->textInput() ?>

    <?= $form->field($model, 'transport_startdate')->textInput() ?>

    <?= $form->field($model, 'transport_enddate')->textInput() ?>

    <?= $form->field($model, 'transport_teachers')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transport_students')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meeting_id')->textInput() ?>

    <?= $form->field($model, 'school_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
