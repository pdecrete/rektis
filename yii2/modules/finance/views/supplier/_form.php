<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\Supplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'suppl_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_vat')->textInput() ?>

    <?= $form->field($model, 'suppl_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_phone')->textInput() ?>

    <?= $form->field($model, 'suppl_fax')->textInput() ?>

    <?= $form->field($model, 'suppl_iban')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_employerid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_taxoffice')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
