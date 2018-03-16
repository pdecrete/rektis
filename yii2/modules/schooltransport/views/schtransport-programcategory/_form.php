<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportProgramcategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schtransport-programcategory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'programcategory_actioncode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'programcategory_actiontitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'programcategory_actionsubcateg')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
