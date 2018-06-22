<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use admapp\Util\Html as admappHtml;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Leave */
/* @var $form yii\widgets\ActiveForm */

$toggle_js = <<<TOGGLEJS
var extra_reason_visible = 10;

// hide empty elements
for (var extra_reason_visible_cnt = 10; extra_reason_visible_cnt >= 2; extra_reason_visible_cnt--) {
    var element = "#leave-extra_reason" + extra_reason_visible_cnt,
        container_element = ".field-leave-extra_reason" + extra_reason_visible_cnt;
    if (!$.trim($(element).val())) {
        $(".form-group" + container_element).hide();
    } else {
        break;
    }
}
extra_reason_visible = extra_reason_visible_cnt;

$("#show_extra_reason").click(function(e) {
    var element;

    e.preventDefault();
    if (extra_reason_visible > 10) {
        alert("Μέχρι 10 αιτιολογήσεις είναι διαθέσιμες")
    } else {
        extra_reason_visible++;
        element = ".field-leave-extra_reason" + extra_reason_visible;
        $(".form-group" + element).toggle();
    }
});
TOGGLEJS;
$this->registerJs($toggle_js, $this::POS_READY);

?>

<div class="leave-form">
    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}{hint}</div>\n<div class=\"col-sm-4\">{error}</div>",
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
    <?= $form->field($model, 'reason')->textInput(['maxlength' => true])->hint(Yii::t('app', 'Reason.hint')) ?>

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

    <div class="row">
        <div class="col-sm-offset-2">
            <p><a href="#" id="show_extra_reason" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span> Εμφάνιση περισσότερων αιτιολογήσεων</a></p>
        </div>
    </div>
    <?php for ($extra_reason_cnt = 1; $extra_reason_cnt <= 10; $extra_reason_cnt++) : ?>
    <?= $form->field($model, "extra_reason{$extra_reason_cnt}")->textarea(['rows' => 4, 'maxlength' => true]) ?>
    <?php endfor; ?>

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
