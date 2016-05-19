<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#personal" aria-controls="personal" role="tab" data-toggle="tab">Προσωπικά</a></li>
      <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab">Υπηρεσιακά</a></li>
    </ul>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane fade-in active" id="personal">
        <br>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'fathersname')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'mothersname')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'tax_identification_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'identity_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'social_security_number')->textInput(['maxlength' => true]) ?>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="service">
        <br>
        <?= $form->field($model, 'status')->widget(Select2::classname(), [
          'data' => \app\models\EmployeeStatus::find()->select(['name', 'id'])->indexBy('id')->column(),
          'options' => ['placeholder' => 'Επιλέξτε...'],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'specialisation')->widget(Select2::classname(), [
          'data' => \app\models\Specialisation::find()->select(["CONCAT(name, ' (', code, ')')", 'id'])->indexBy('id')->column(),
          'options' => ['placeholder' => 'Επιλέξτε...'],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'identification_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'appointment_fek')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'appointment_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Εισάγετε ημερομηνία...'],
            'pluginOptions' => [
                'autoclose'=>true
            ]
        ]);
        ?>

        <?= $form->field($model, 'service_organic')->widget(Select2::classname(), [
          'data' => \app\models\Service::find()->select(['name', 'id'])->indexBy('id')->column(),
          'options' => ['placeholder' => 'Επιλέξτε...'],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'service_serve')->widget(Select2::classname(), [
          'data' => \app\models\Service::find()->select(['name', 'id'])->indexBy('id')->column(),
          'options' => ['placeholder' => 'Επιλέξτε...'],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>

        <?= $form->field($model, 'position')->widget(Select2::classname(), [
          'data' => \app\models\Position::find()->select(['name', 'id'])->indexBy('id')->column(),
          'options' => ['placeholder' => 'Επιλέξτε...'],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'rank')->widget(Select2::classname(), [
          'data' => \app\models\Employee::ranksList(),
          'options' => ['placeholder' => 'Επιλέξτε...'],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>

        <?= $form->field($model, 'rank_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Εισάγετε ημερομηνία...'],
            'pluginOptions' => [
                'autoclose'=>true
            ]
        ]);
        ?>
        <?= $form->field($model, 'pay_scale')->widget(Select2::classname(), [
          'data' => \app\models\Employee::payscaleList(),
          'options' => ['placeholder' => 'Επιλέξτε...'],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'pay_scale_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Εισάγετε ημερομηνία...'],
            'pluginOptions' => [
                'autoclose'=>true
            ]
        ]);
        ?>
        <?= $form->field($model, 'service_adoption')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'service_adoption_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Εισάγετε ημερομηνία...'],
            'pluginOptions' => [
                'autoclose'=>true
            ]
        ]);
        ?>

        <?= $form->field($model, 'master_degree')->textInput() ?>
        <?= $form->field($model, 'doctorate_degree')->textInput() ?>
        <?= $form->field($model, 'work_experience')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>
      </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Ενημέρωση', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
