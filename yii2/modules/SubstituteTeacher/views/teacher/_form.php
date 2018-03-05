<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Teacher */
/* @var $form yii\widgets\ActiveForm */

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Address: " + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Address: " + (index + 1))
    });
});
';

$this->registerJs($js);
$firstModelPlacementPreference = reset($modelsPlacementPreferences);
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?=
    $form->field($model, 'registry_id')->widget(Select2::classname(), [
        'data' => TeacherRegistry::defaultSelectables(),
        'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
        'pluginOptions' => [
            'multiple' => false,
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'year')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'status')->dropDownList(Teacher::getChoices('status'), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?= $form->field($model, 'points')->textInput(['maxlength' => true]) ?>

    <?php 
    DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'min' => 0,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $firstModelPlacementPreference,
        'formId' => 'dynamic-form',
        'formFields' => [
            'prefecture_id',
            'school_type',
            'order'
        ],
    ]);
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo Yii::t('substituteteacher', 'Placement preferences'); ?>
            <button type="button" class="add-item btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span> <?php echo Yii::t('substituteteacher', 'Add new preference'); ?></button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body"><!-- widgetContainer -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><?php echo $firstModelPlacementPreference->getAttributeLabel('prefecture_id'); ?></th>
                        <th><?php echo $firstModelPlacementPreference->getAttributeLabel('school_type'); ?></th>
                        <th><?php echo $firstModelPlacementPreference->getAttributeLabel('order'); ?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="container-items">
                <?php foreach ($modelsPlacementPreferences as $index => $modelPlacementPreference): ?>
                    <tr class="item">
                        <td>
                            <?php
                                // necessary for update action.
                                if (!$modelPlacementPreference->isNewRecord) {
                                    echo Html::activeHiddenInput($modelPlacementPreference, "[{$index}]id");
                                }
                            ?>
                            <?= $form->field($modelPlacementPreference, "[{$index}]prefecture_id")->dropDownList(Prefecture::defaultSelectables(), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                            <?php 
                                $teacher_errors = $modelPlacementPreference->getErrors('teacher_id');
                                if (!empty($teacher_errors)) :
                            ?>
                                <div class="text-danger"><?= implode(', ', $teacher_errors) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $form->field($modelPlacementPreference, "[{$index}]school_type")->dropDownList(PlacementPreference::getChoices('school_type'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelPlacementPreference, "[{$index}]order")->textInput(['type' => 'number', 'min' => 0])->label(false) ?>
                        </td>
                        <td class="col-sm-1 text-center">
                            <button type="button" class="remove-item btn btn-danger btn-sm"><span class="glyphicon glyphicon-minus"></span></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
