<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admapp\Util\Html as admappHtml;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-type-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'limit')->widget(MaskedInput::classname(), ['name' => 'limit', 'mask' => '9{1,2}']) ?>
    <?= $form->field($model, 'reason_num')->widget(MaskedInput::classname(), ['name' => 'reason_num', 'mask' => '9{1,2}']) ?>
    <?= $form->field($model, 'check')->checkbox($options = [], $enclosedByLabel = false ) ?>
	<?= $form->field($model, 'schoolyear_based')->radioList(['0' => 'στο ημερολογιακό έτος', '1' => 'στο σχολικό έτος'], $options = ['separator' => " <br> "], $enclosedByLabel = false ) ?>

    <?php
    $availabletemplatefilenames = $model->availabletemplatefilenames;
    $atf = array_combine($availabletemplatefilenames, $availabletemplatefilenames);
    echo $form->field($model, 'templatefilename')->dropDownList($atf, ['prompt' => Yii::t('app', 'Select a template file')])
    ?>
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
