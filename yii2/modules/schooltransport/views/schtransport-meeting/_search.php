<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportMeetingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schtransport-meeting-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'meeting_id') ?>

    <?= $form->field($model, 'meeting_city') ?>

    <?= $form->field($model, 'meeting_country') ?>

    <?= $form->field($model, 'meeting_startdate') ?>

    <?= $form->field($model, 'meeting_enddate') ?>

    <?php // echo $form->field($model, 'program_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
