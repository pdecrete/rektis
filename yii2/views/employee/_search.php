<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'surname') ?>

    <?= $form->field($model, 'fathersname') ?>

    <?php // echo $form->field($model, 'mothersname') ?>

    <?php // echo $form->field($model, 'tax_identification_number') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'telephone') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'identity_number') ?>

    <?php // echo $form->field($model, 'social_security_number') ?>

    <?php // echo $form->field($model, 'specialisation') ?>

    <?php // echo $form->field($model, 'identification_number') ?>

    <?php // echo $form->field($model, 'appointment_fek') ?>

    <?php // echo $form->field($model, 'appointment_date') ?>

    <?php // echo $form->field($model, 'service_organic') ?>

    <?php // echo $form->field($model, 'service_serve') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'rank') ?>

    <?php // echo $form->field($model, 'rank_date') ?>

    <?php // echo $form->field($model, 'pay_scale') ?>

    <?php // echo $form->field($model, 'pay_scale_date') ?>

    <?php // echo $form->field($model, 'service_adoption') ?>

    <?php // echo $form->field($model, 'service_adoption_date') ?>

    <?php // echo $form->field($model, 'master_degree') ?>

    <?php // echo $form->field($model, 'doctorate_degree') ?>

    <?php // echo $form->field($model, 'work_experience') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'create_ts') ?>

    <?php // echo $form->field($model, 'update_ts') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
