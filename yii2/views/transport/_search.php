<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'employee') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?= $form->field($model, 'decision_protocol') ?>

    <?= $form->field($model, 'decision_protocol_date') ?>

    <?= $form->field($model, 'application_protocol') ?>

    <?= $form->field($model, 'application_protocol_date') ?>

    <?php // echo $form->field($model, 'application_date') ?>

    <?php // echo $form->field($model, 'accompanying_document') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'from_to') ?>

    <?php // echo $form->field($model, 'base') ?>

    <?php // echo $form->field($model, 'days_applied') ?>

    <?php // echo $form->field($model, 'klm') ?>

    <?php // echo $form->field($model, 'mode') ?>

    <?php // echo $form->field($model, 'ticket_value') ?>

    <?php // echo $form->field($model, 'klm_reimb') ?>

    <?php // echo $form->field($model, 'days_out') ?>

    <?php // echo $form->field($model, 'day_reimb') ?>

    <?php // echo $form->field($model, 'night_reimb') ?>

    <?php // echo $form->field($model, 'reimbursement') ?>

    <?php // echo $form->field($model, 'mtpy') ?>

    <?php // echo $form->field($model, 'pay_amount') ?>

    <?php // echo $form->field($model, 'expense_details') ?>

    <?php // echo $form->field($model, 'comment') ?>
    
    <?php // echo $form->field($model, 'funds1') ?>
    
    <?php // echo $form->field($model, 'funds2') ?>
    
    <?php // echo $form->field($model, 'funds3') ?>

    <?php // echo $form->field($model, 'code719') ?>
    
    <?php // echo $form->field($model, 'code721') ?>
    
    <?php // echo $form->field($model, 'code722') ?>
    
    <?php // echo $form->field($model, 'count_flag') ?>
    
    <?php // echo $form->field($model, 'create_ts') ?>

    <?php // echo $form->field($model, 'update_ts') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
