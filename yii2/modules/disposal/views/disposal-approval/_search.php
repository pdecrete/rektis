<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApprovalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disposal-approval-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'approval_id') ?>

    <?= $form->field($model, 'approval_regionaldirectprotocol') ?>

    <?= $form->field($model, 'approval_localdirectprotocol') ?>

    <?= $form->field($model, 'approval_notes') ?>

    <?= $form->field($model, 'approval_file') ?>

    <?php // echo $form->field($model, 'approval_signedfile')?>

    <?php // echo $form->field($model, 'approval_created_at')?>

    <?php // echo $form->field($model, 'approval_updated_at')?>

    <?php // echo $form->field($model, 'approval_created_by')?>

    <?php // echo $form->field($model, 'approval_updated_by')?>

    <?php // echo $form->field($model, 'approvaltype_id')?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
