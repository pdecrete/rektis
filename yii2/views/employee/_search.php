<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
   <div class="row">
      <div class="col-md-4">
         <?= $form->field($model, 'name') ?>
         
         <?= $form->field($model, 'surname') ?>
         
         <?= $form->field($model, 'tax_identification_number') ?>
         
         <?= $form->field($model, 'identity_number') ?>
         
         <?= $form->field($model, 'social_security_number')->widget(MaskedInput::classname(),['name' => 'social_security_number','mask' => '99999999999']) ?>
      </div>
      <div class="col-md-4">
         <?= $form->field($model, 'status')->widget(Select2::classname(), [
          'data' => \app\models\EmployeeStatus::find()->select(['name', 'name'])->indexBy('name')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
         <?= $form->field($model, 'position')->widget(Select2::classname(), [
          'data' => \app\models\Position::find()->select(['name', 'name'])->indexBy('name')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'specialisation')->widget(Select2::classname(), [
          'data' => \app\models\Specialisation::find()->select(["CONCAT(name, ' (', code, ')')", 'code'])->indexBy('code')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'rank')->widget(Select2::classname(), [
          'data' => \app\models\Employee::ranksList(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
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
         
      </div>
      <div class="col-md-4">
         <?= $form->field($model, 'identification_number')->widget(MaskedInput::classname(),['name' => 'identification_number','mask' => '999999']) ?>
         <?= $form->field($model, 'service_organic')->widget(Select2::classname(), [
          'data' => \app\models\Service::find()->select(['name', 'name'])->indexBy('name')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>

         <?= $form->field($model, 'service_serve')->widget(Select2::classname(), [
          'data' => \app\models\Service::find()->select(['name', 'name'])->indexBy('name')->column(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
        <?= $form->field($model, 'deleted')->checkbox(['label' => Yii::t('app','Search deleted employees only')]) ?>
      </div>
    </div>
    
    
    <?php // echo $form->field($model, 'email') ?>
    <?php // echo $form->field($model, 'telephone') ?>
    <?php // echo $form->field($model, 'address') ?>
    <?php // echo $form->field($model, 'appointment_fek') ?>
    <?php // echo $form->field($model, 'appointment_date') ?>
    <?php // echo $form->field($model, 'position') ?>
    <?php // echo $form->field($model, 'rank_date') ?>
    <?php // echo $form->field($model, 'pay_scale_date') ?>
    <?php // echo $form->field($model, 'service_adoption') ?>
    <?php // echo $form->field($model, 'service_adoption_date') ?>
    <?php // echo $form->field($model, 'master_degree') ?>
    <?php // echo $form->field($model, 'doctorate_degree') ?>
    <?php // echo $form->field($model, 'work_experience') ?>
    <?php // echo $form->field($model, 'comments') ?>
    <?php // echo $form->field($model, 'create_ts') ?>
    <?php // echo $form->field($model, 'update_ts') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
