<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\Specialisation;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherBoard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-board-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'teacher_id')->dropDownList(Teacher::defaultSelectables(), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?= $form->field($model, 'specialisation_id')->dropDownList(Specialisation::selectables(), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?= $form->field($model, 'board_type')->dropDownList(TeacherBoard::getChoices('board_type'), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <?= $form->field($model, 'points')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(Teacher::getChoices('status'), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>