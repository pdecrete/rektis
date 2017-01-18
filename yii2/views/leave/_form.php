<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admapp\Util\Html as admappHtml;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Leave */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-form">
    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>
    <?=
    $form->field($model, 'employee')->widget(Select2::classname(), [
        'data' => \app\models\Employee::find()->select(["CONCAT(surname, ' ', name) as fname", 'id'])->orderBy('fname')->indexBy('id')->column(),
        'options' => ['placeholder' => 'Επιλέξτε...'],
    ]);
    ?>
    <?=
    $form->field($model, 'type')->widget(Select2::classname(), [
        'data' => \app\models\LeaveType::find()->select(['name', 'id'])->indexBy('id')->column(),
        'options' => ['placeholder' => 'Επιλέξτε...'],
    ]);
    ?>

    <?= $form->field($model, 'decision_protocol')->textInput() ?>
    <?=
    $form->field($model, 'decision_protocol_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE
    ]);
    ?>

    <?= $form->field($model, 'application_protocol')->textInput() ?>
    <?=
    $form->field($model, 'application_protocol_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE
    ]);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= admappHtml::displayCopyFieldValueButton($model, 'decision_protocol_date', 'application_protocol_date', 'Αντιγραφή από ' . $model->getAttributeLabel('decision_protocol_date'), null, '-disp'); ?>
        </div>
    </div>

    <?=
    $form->field($model, 'application_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE
    ]);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= admappHtml::displayCopyFieldValueButton($model, 'application_protocol_date', 'application_date', 'Αντιγραφή από ' . $model->getAttributeLabel('application_protocol_date'), null, '-disp'); ?>
        </div>
    </div>
    <?= $form->field($model, 'accompanying_document')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'duration')->widget(MaskedInput::classname(), ['name' => 'duration', 'mask' => '9{1,2}']) ?>
    <?=
    $form->field($model, 'start_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE
    ]);
    ?>
    <?=
    $form->field($model, 'end_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE
    ]);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= admappHtml::displayCopyFieldValueButton($model, 'start_date', 'end_date', 'Αντιγραφή από ' . $model->getAttributeLabel('start_date'), null, '-disp'); ?>
        </div>
    </div>
    <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'extra_reason1')->textarea(['rows' => 4, 'maxlength' => true]) ?>
    <?= $form->field($model, 'extra_reason2')->textarea(['rows' => 4, 'maxlength' => true]) ?>
    <?= $form->field($model, 'extra_reason3')->textarea(['rows' => 4, 'maxlength' => true]) ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 4, 'maxlength' => true]) ?>

    <?php if (!$model->isNewRecord) : ?>
        <?= admappHtml::displayValueOfField($model, 'create_ts', 2, 6) ?>
        <?= admappHtml::displayValueOfField($model, 'update_ts', 2, 6) ?>
    <?php endif; ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Ενημέρωση στοιχείων', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Επιστροφή', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
//$test = '
//$(function () {
//    var $src = $("#three"),
//        $dst = $("#four");
//    $src.on('input', function () {
//        $dst.val($src.val());
//    });
//});
//';
