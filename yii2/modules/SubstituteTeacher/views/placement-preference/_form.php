<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\modules\SubstituteTeacher\models\PlacementPreference;
use app\modules\SubstituteTeacher\models\TeacherRegistry;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\PlacementPreference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="placement-preference-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'teacher_id')->dropDownList(TeacherRegistry::defaultSelectables(), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?= $form->field($model, 'prefecture_id')->dropDownList(Prefecture::defaultSelectables(), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?= $form->field($model, 'school_type')->dropDownList(PlacementPreference::getChoices('school_type'), ['prompt' => Yii::t('substituteteacher', 'Choose...')])->label(false) ?>

    <?= $form->field($model, 'order')->textInput()->textInput(['type' => 'number', 'min' => 0]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
