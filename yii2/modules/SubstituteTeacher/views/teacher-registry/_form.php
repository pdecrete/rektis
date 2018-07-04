<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Specialisation;
use kartik\datecontrol\DateControl;
use dosamigos\switchinput\SwitchBox;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\TeacherRegistry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacher-registry-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?=
                $form->field($model, 'specialisation_ids')->widget(Select2::classname(), [
                    'data' => Specialisation::selectables(),
                    'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
                    'pluginOptions' => [
                        'multiple' => true,
                        'allowClear' => true
                    ],
                ]);

                ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'fathername')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'mothername')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'gender')->dropDownList(TeacherRegistry::getChoices('gender'), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'marital_status')->dropDownList(TeacherRegistry::getChoices('marital_status'), ['prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'protected_children')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'birthdate')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE
                ]);
                ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'birthplace')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'mobile_phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'home_phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'work_phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'home_address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'postal_code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'social_security_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tax_identification_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tax_service')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'identity_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'iban')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="well well-sm">
        <h3>Τυπικά προσόντα</h3>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'aei')->widget(SwitchBox::className(), [
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
                <?= $form->field($model, 'tei')->widget(SwitchBox::className(), [
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
                <?= $form->field($model, 'epal')->widget(SwitchBox::className(), [
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
                <?= $form->field($model, 'iek')->widget(SwitchBox::className(), [
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
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'sign_language')->widget(SwitchBox::className(), [
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
            <div class="col-md-6">
                <?= $form->field($model, 'braille')->widget(SwitchBox::className(), [
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
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('substituteteacher', 'Create') : Yii::t('substituteteacher', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>