<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\SubstituteTeacher\models\Call;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="placement-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'date')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE
                ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'call_id')->widget(Select2::classname(), [
                'data' => Call::defaultSelectables(),
                'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
                'pluginOptions' => [
                    'multiple' => false,
                    'allowClear' => false
                ],
            ])->hint(Yii::t('substituteteacher', 'Select only if placement is performed in the context of a call'));

            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'decision')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'decision_board')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'comments')->textarea(['rows' => 3]) ?>

    <?php if (!$model->isNewRecord): ?>
    <div class="row">
        <div class="form-group">
            <label class="col-sm-2 control-label">
                <?php echo Yii::t('substituteteacher', 'Created At'); ?>
            </label>
            <div class="col-sm-10">
                <p class="form-control-static">
                    <?php echo $model->created_at; ?>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">
                <?php echo Yii::t('substituteteacher', 'Updated At'); ?>
            </label>
            <div class="col-sm-10">
                <p class="form-control-static">
                    <?php echo $model->updated_at; ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>