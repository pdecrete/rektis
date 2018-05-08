<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportMeeting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schtransport-meeting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'meeting_city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meeting_country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meeting_startdate')->textInput() ?>

    <?= $form->field($model, 'meeting_enddate')->textInput() ?>

    <?= $form->field($model, 'program_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
