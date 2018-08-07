<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use kartik\select2\Select2;
use dosamigos\switchinput\SwitchBox;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\TeacherBoard;

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-serial-number").each(function(index) {
        jQuery(this).html("" + (index + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-serial-number").each(function(index) {
        jQuery(this).html("" + (index + 1))
    });
});

var expconvert = function (value) {
    var days = value % 30;
    var months_rem = parseInt((value - days) / 30);
    var months = months_rem % 12;
    var years = parseInt(months_rem / 12);
    return "" + years + "E " + months + "M " + days + "H";
};

var expdisplay = function (elem) {
    var value = parseInt(elem.val()); 
    if (value > 0) {
        elem.next(".hinter").html(expconvert(value));
    } else {
        elem.next(".hinter").html("...");
    }
}

$(".expconv").each(function (idx) {
    expdisplay($(this));
});

$(".expconv").on("change paste keyup", function() {
    expdisplay($(this));
});
';

$this->registerJs($js);
$firstModelPlacementPreference = reset($modelsPlacementPreferences);
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="row">
        <div class="col-md-9">
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
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'year')->textInput(['type' => 'number']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'public_experience')->textInput(['type' => 'number', 'class' => 'form-control expconv'])->hint('...', ['class' => 'hinter']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'smeae_keddy_experience')->textInput(['type' => 'number', 'class' => 'form-control expconv'])->hint('...', ['class' => 'hinter']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'disability_percentage')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'disabled_children')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'many_children')->widget(SwitchBox::className(), [
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
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'three_children')->widget(SwitchBox::className(), [
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
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo Yii::t('substituteteacher', 'Teacher boards'); ?>
        </div>
        <div class="panel-body">
            <?php if (!isset($modelsBoards)) : ?>
            <p>Μπορείτε να προσθέσετε στοιχεία πινάκων διορισμών
                <strong>μετά την δημιουργία</strong>.</p>
            <?php elseif (empty($modelsBoards)) : ?>
            <p>Δεν υπάρχουν στοιχεία.</p>
            <?php else: ?>
            <?php 
            $firstModelBoard = reset($modelsBoards);
            ?>
            <table class="table table-striped table-hover">
                <caption>Εάν ο καθηγητής δεν υπάγεται σε πίνακα κάποιας ειδικότητας, εισάγετε κενό στην αντίστοιχη σειρά κατάταξης
                    στον πίνακα και στα μόρια και αφαιρέστε την επιλογή τύπου πίνακα διορισμού.</caption>
                <thead>
                    <tr>
                        <th>
                            <?php echo Yii::t('substituteteacher', 'Order in board'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelBoard->getAttributeLabel('board_type'); ?>
                        </th>
                        <th>
                            <?php echo Yii::t('substituteteacher', 'Specialisation'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelBoard->getAttributeLabel('points'); ?>
                        </th>
                        <th>
                            <?php echo Yii::t('substituteteacher', 'Status'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modelsBoards as $index => $modelBoard): ?>
                    <tr class="item">
                        <td>
                            <?php
                                // necessary for update action.
                                if (!$modelBoard->isNewRecord) {
                                    echo Html::activeHiddenInput($modelBoard, "[{$index}]id");
                                }
                            ?>
                            <?= $form->field($modelBoard, "[{$index}]order")->textInput(['type' => 'number', 'min' => 0])->label(false) ?>
                            <?php 
                                $teacher_errors = $modelBoard->getErrors('teacher_id');
                                if (!empty($teacher_errors)) :
                            ?>
                            <div class="text-danger">
                                <?= implode(', ', $teacher_errors) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $form->field($modelBoard, "[{$index}]board_type")->dropDownList(TeacherBoard::getChoices('board_type'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                        </td>
                        <td>
                            <?php echo $modelBoard->specialisation->code; ?>
                            <?= $form->field($modelBoard, "[{$index}]specialisation_id")->hiddenInput()->label(false) ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelBoard, "[{$index}]points")->textInput()->label(false) ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelBoard, "[{$index}]status")->dropDownList(Teacher::getChoices('status'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <?php 
    DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'min' => 1,
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
            <button type="button" class="add-item btn btn-success btn-xs">
                <span class="glyphicon glyphicon-plus"></span>
                <?php echo Yii::t('substituteteacher', 'Add new preference'); ?>
            </button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <!-- widgetContainer -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-xs-1">#</th>
                        <th>
                            <?php echo $firstModelPlacementPreference->getAttributeLabel('prefecture_id'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelPlacementPreference->getAttributeLabel('school_type'); ?>
                        </th>
                        <th>
                            <?php echo $firstModelPlacementPreference->getAttributeLabel('order'); ?>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="container-items">
                    <?php foreach ($modelsPlacementPreferences as $index => $modelPlacementPreference): ?>
                    <tr class="item">
                        <td>
                            <span class="badge panel-serial-number">
                                <?php echo $index + 1; ?>
                            </span>
                        </td>
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
                            <div class="text-danger">
                                <?= implode(', ', $teacher_errors) ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $form->field($modelPlacementPreference, "[{$index}]school_type")->dropDownList(PlacementPreference::getChoices('school_type'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelPlacementPreference, "[{$index}]order")->textInput(['type' => 'number', 'min' => 0])->label(false) ?>
                        </td>
                        <td class="col-sm-1 text-center">
                            <button type="button" class="remove-item btn btn-danger btn-sm">
                                <span class="glyphicon glyphicon-minus"></span>
                            </button>
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