<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceDeduction */
/* @var $form yii\widgets\ActiveForm */

$model->deduct_downlimit = Money::toCurrency($model->deduct_downlimit);
$model->deduct_uplimit = Money::toCurrency($model->deduct_uplimit);
$model->deduct_percentage = Money::toPercentage($model->deduct_percentage);
?>

<div class="finance-deduction-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deduct_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deduct_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deduct_percentage')->textInput() ?>

    <?= $form->field($model, 'deduct_downlimit')->textInput(['maxlength' => true, 
                                					    'type' => 'number', 
                                					    'min' => "0.00" , 
                                					    'step' => '0.01', 
                                					    'style' => 'text-align: left', 
                                                        'value' => $model['deduct_downlimit']]) ?>

    <?= $form->field($model, 'deduct_uplimit')->textInput(['maxlength' => true, 
                                					    'type' => 'number', 
                                					    'min' => "0.00" , 
                                					    'step' => '0.01', 
                                					    'style' => 'text-align: left', 
                                                        'value' => $model['deduct_uplimit']]) ?>

    <div class="form-group text-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Module::t('modules/finance/app', 'Create') : Module::t('modules/finance/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
