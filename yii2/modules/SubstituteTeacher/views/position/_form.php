<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use dosamigos\switchinput\SwitchBox;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Position */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="position-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'operation_id')->widget(Select2::classname(), [
        'data' => \app\modules\SubstituteTeacher\models\Operation::selectables(),
        'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);

    ?>
    <?=
    $form->field($model, 'specialisation_id')->widget(Select2::classname(), [
        'data' => app\models\Specialisation::selectables(),
        'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);

    ?>
    <?=
    $form->field($model, 'prefecture_id')->widget(Select2::classname(), [
        'data' => app\modules\SubstituteTeacher\models\Prefecture::selectables(),
        'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);

    ?>

    <div class="row">
        <div class="col-sm-4">
            <?=
            $form->field($model, 'position_has_type')->widget(SwitchBox::className(), [
                'options' => [
                    'label' => '',
//                    'onchange' => 'js:toggleInput()'
                ],
                'clientOptions' => [
//                    'size' => 'large',
                    'onColor' => 'primary',
                    'onText' => Yii::t('substituteteacher', 'Teachers'),
                    'offColor' => 'primary',
                    'offText' => Yii::t('substituteteacher', 'Hours'),
                ]
            ]);
            $checkbox_id = Html::getInputId($model, 'position_has_type');
            $hours_count_id = Html::getInputId($model, 'hours_count');
            $covered_hours_count_id = Html::getInputId($model, 'covered_hours_count');
            $teachers_count_id = Html::getInputId($model, 'teachers_count');
            $covered_teachers_count_id = Html::getInputId($model, 'covered_teachers_count');
            $switch_js = <<<JS
$("#{$teachers_count_id}").prop("disabled", true);
$("#{$covered_teachers_count_id}").prop("disabled", true);
$('#{$checkbox_id}').on('switchChange.bootstrapSwitch', function(event, state) {
    if (true === state) {
        $("#{$hours_count_id}").prop("disabled", true);
        $("#{$covered_hours_count_id}").prop("disabled", true);
        $("#{$teachers_count_id}").prop("disabled", false);
        $("#{$covered_teachers_count_id}").prop("disabled", false);
    } else {
        $("#{$hours_count_id}").prop("disabled", false);
        $("#{$covered_hours_count_id}").prop("disabled", false);
        $("#{$teachers_count_id}").prop("disabled", true);
        $("#{$covered_teachers_count_id}").prop("disabled", true);
    }
});
JS;
            $this->registerJs($switch_js, View::POS_READY, 'my-switch-handler');

            ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'teachers_count')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'hours_count')->textInput(['type' => 'number']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <?= $form->field($model, 'covered_teachers_count')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'covered_hours_count')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <?= $form->field($model, 'whole_teacher_hours')->textInput(['type' => 'number']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
