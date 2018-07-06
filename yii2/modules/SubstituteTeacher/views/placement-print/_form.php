<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\SubstituteTeacher\models\PlacementPrint;
use app\modules\SubstituteTeacher\models\Placement;
use dosamigos\switchinput\SwitchBox;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\PlacementPrint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="placement-print-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(PlacementPrint::getTypeOptions(), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?= $form->field($model, 'placement_id')->dropDownList(Placement::defaultSelectables(), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?php // echo $form->field($model, 'placement_teacher_id')->textInput() ?>

    <?= $form->field($model, 'filename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'deleted')->widget(SwitchBox::className(), [
                    'options' => [
                        'label' => '',
                    ],
                    'clientOptions' => [
                       'size' => 'small',
                        'onColor' => 'success',
                        'onText' => Yii::t('substituteteacher', 'YES'),
                        'offText' => Yii::t('substituteteacher', 'No'),
                    ]
                ]);
                ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

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
