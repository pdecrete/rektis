<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use app\modules\SubstituteTeacher\models\Operation;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Operation */
/* @var $form yii\widgets\ActiveForm */

$template_filenames = Operation::getAvailableTemplates();
?>

<div class="operation-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'year')->dropdownList(Operation::getYearChoices()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?=
    $form->field($model, 'specialisation_ids')->widget(Select2::classname(), [
        'data' => \app\modules\SubstituteTeacher\models\Specialisation::selectables(),
        'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
        'pluginOptions' => [
            'multiple' => true,
            'allowClear' => true
        ],
    ]);

    ?>

    <?= $form->field($model, 'logo')->dropdownList(Operation::getLogoChoices()) ?>
    <?= $form->field($model, 'contract_template')->dropdownList(array_combine($template_filenames, $template_filenames)) ?>
    <?= $form->field($model, 'summary_template')->dropdownList(array_combine($template_filenames, $template_filenames)) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
