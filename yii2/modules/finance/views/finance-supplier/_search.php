<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceSupplierSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-supplier-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'suppl_id') ?>

    <?= $form->field($model, 'suppl_name') ?>

    <?= $form->field($model, 'suppl_vat') ?>

    <?= $form->field($model, 'suppl_address') ?>

    <?= $form->field($model, 'suppl_phone') ?>

    <?php // echo $form->field($model, 'suppl_fax') ?>

    <?php // echo $form->field($model, 'suppl_iban') ?>

    <?php // echo $form->field($model, 'suppl_employerid') ?>

    <?php // echo $form->field($model, 'taxoffice_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
