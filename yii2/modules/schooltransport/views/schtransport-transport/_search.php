<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schtransport-transport-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'transport_id') ?>

    <?= $form->field($model, 'transport_submissiondate') ?>

    <?= $form->field($model, 'transport_startdate') ?>

    <?= $form->field($model, 'transport_enddate') ?>

    <?= $form->field($model, 'transport_teachers') ?>

    <?php // echo $form->field($model, 'transport_students') ?>

    <?php // echo $form->field($model, 'transport_localdirectorate_protocol') ?>

    <?php // echo $form->field($model, 'transport_pde_protocol') ?>

    <?php // echo $form->field($model, 'transport_remarks') ?>

    <?php // echo $form->field($model, 'transport_datesentapproval') ?>

    <?php // echo $form->field($model, 'transport_dateprotocolcompleted') ?>

    <?php // echo $form->field($model, 'transport_approvalfile') ?>

    <?php // echo $form->field($model, 'transport_signedapprovalfile') ?>

    <?php // echo $form->field($model, 'meeting_id') ?>

    <?php // echo $form->field($model, 'school_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
