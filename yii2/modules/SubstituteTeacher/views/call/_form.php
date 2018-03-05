<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Call */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="call-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?=
            $form->field($model, 'application_start')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE
            ]);

            ?>
            <?= $form->field($model, 'application_start_ts', ['template' => '{error}'])->textInput() ?>
        </div>
        <div class="col-sm-6">
            <?=
            $form->field($model, 'application_end')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE
            ]);

            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
