<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use app\modules\SubstituteTeacher\models\Specialisation;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Call */
/* @var $form yii\widgets\ActiveForm */

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
';

$this->registerJs($js);

$firstCallTeacherModel = reset($modelsCallTeacherSpecialisation);

?>

<div class="call-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput(['type' => 'number', 'min' => date('Y') - 2]) ?>

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


    <?php 
    DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'min' => 0,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $firstCallTeacherModel,
        'formId' => 'dynamic-form',
        'formFields' => [
            'specialisation_id',
            'teachers'
        ],
    ]);
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo Yii::t('substituteteacher', 'Teachers to appoint'); ?>
            <button type="button" class="add-item btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span> <?php echo Yii::t('substituteteacher', 'Add teachers'); ?></button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body"><!-- widgetContainer -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-xs-1">#</th>
                        <th><?php echo $firstCallTeacherModel->getAttributeLabel('specialisation_id'); ?></th>
                        <th><?php echo $firstCallTeacherModel->getAttributeLabel('teachers'); ?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody class="container-items">

                <?php foreach (array_keys($modelsCallTeacherSpecialisation) as $serial => $index): ?>
                    <?php $modelCTS = $modelsCallTeacherSpecialisation[$index]; ?>
                    <tr class="item">
                        <td><span class="badge panel-serial-number"><?php echo $serial + 1; ?></span></td>
                        <td>
                            <?php
                                // necessary for update action.
                                if (!$modelCTS->isNewRecord) {
                                    echo Html::activeHiddenInput($modelCTS, "[{$index}]id");
                                }
                            ?>
                            <?= $form->field($modelCTS, "[{$index}]specialisation_id")->dropDownList(ArrayHelper::map(Specialisation::find()->all(), 'id', 'label'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>
                            <?php 
                                $call_errors = $modelCTS->getErrors('call_id');
                                if (!empty($call_errors)) :
                            ?>
                                <div class="text-danger"><?= implode(', ', $call_errors) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="col-sm-2">
                            <?= $form->field($modelCTS, "[{$index}]teachers")->textInput(['type' => 'number', 'min' => 0])->label(false) ?>
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
