<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;


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
   <!-- display error summary -->
   <?= $form->errorSummary($model); ?>

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#personal" aria-controls="personal" role="tab" data-toggle="tab"><?= Yii::t('app', 'Personal') ?></a></li>
      <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab"><?= Yii::t('app', 'Professional') ?></a></li>
    </ul>

    <div class="tab-content">
      <!-- Personal Tab -->
      <div role="tabpanel" class="tab-pane fade-in active" id="personal">
        <br>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'fathersname')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'mothersname')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'tax_identification_number')->widget(MaskedInput::classname(),['name' => 'tin','mask' => '999999999']) ?>
        <?= $form->field($model, 'email')->widget(MaskedInput::classname(),['name' => 'email','clientOptions' => [
               'alias' =>  'email'
            ],
         ]) ?>
        <?= $form->field($model, 'telephone')->widget(MaskedInput::classname(),['name' => 'telephone','mask' => '9999-999999']) ?>
        <?= $form->field($model, 'mobile')->widget(MaskedInput::classname(),['name' => 'mobile','mask' => '9999-999999']) ?>
        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'identity_number')->widget(MaskedInput::classname(),['name' => 'identity_number', 'clientOptions' => ['alias' => 'Regex', 'regex' => '[A-ZΑ-Ω]{1,2}[0-9]{6}]']]) ?>
        
        <?= $form->field($model, 'social_security_number')->widget(MaskedInput::classname(),['name' => 'social_security_number','mask' => '99999999999']) ?>
      </div>
      
      <!-- Service Tab -->
      <div role="tabpanel" class="tab-pane fade" id="service">
        <br>
        <?= $form->field($model, 'status')->widget(Select2::classname(), [
          'data' => \app\models\EmployeeStatus::find()->select(['name', 'id'])->orderby('name')->indexBy('id')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'position')->widget(Select2::classname(), [
          'data' => \app\models\Position::find()->select(['name', 'id'])->orderby('name')->indexBy('id')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'specialisation')->widget(Select2::classname(), [
          'data' => \app\models\Specialisation::find()->select(["CONCAT(name, ' (', code, ')')", 'id'])->indexBy('id')->orderby('name')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'identification_number')->widget(MaskedInput::classname(),['name' => 'identification_number','mask' => '999999'])
        ?>
        <?= $form->field($model, 'service_organic')->widget(Select2::classname(), [
          'data' => \app\models\Service::find()->select(['name', 'id'])->indexBy('id')->orderby('name')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'service_serve')->widget(Select2::classname(), [
          'data' => \app\models\Service::find()->select(['name', 'id'])->indexBy('id')->orderby('name')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>

        <hr>
        <?= $form->field($model, 'appointment_fek')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'appointment_date')->widget(DateControl::classname(), [
             'type'=>DateControl::FORMAT_DATE
         ]);
        ?>
        <?= $form->field($model, 'service_adoption')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'service_adoption_date')->widget(DateControl::classname(), [
             'type'=>DateControl::FORMAT_DATE
         ]);
        ?>
        <hr>
        
        <?= $form->field($model, 'rank')->widget(Select2::classname(), [
          'data' => \app\models\Employee::ranksList(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>

        <?= $form->field($model, 'rank_date')->widget(DateControl::classname(), [
             'type'=>DateControl::FORMAT_DATE
         ]);
        ?>
        <?= $form->field($model, 'pay_scale')->widget(Select2::classname(), [
          'data' => \app\models\Employee::payscaleList(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'pay_scale_date')->widget(DateControl::classname(), [
             'type'=>DateControl::FORMAT_DATE
         ]);
        ?>
        <hr>

        <?= $form->field($model, 'master_degree')->textInput() ?>
        <?= $form->field($model, 'doctorate_degree')->textInput() ?>
        <?= $form->field($model, 'work_experience')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>
      </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
