<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="finance-expenditure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'exp_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_id')->dropDownList(
        ArrayHelper::map($suppliers,'suppl_id', 'suppl_name'),
        ['prompt'=> Module::t('modules/finance/app', 'Supplier')])
    ?>

    <?= $form->field($model, 'fpa_value')->dropDownList(
        ArrayHelper::map($vat_levels,'fpa_value', 'fpa_value'),
        ['prompt'=> Module::t('modules/finance/app', 'VAT')])
    ?>
    <hr />
    <h3><?= Module::t('modules/finance/app', 'Assign withdrawals');?></h3>
    <?php 
        foreach($expendwithdrawals_models as $index => $expendwithdrawals_model){
            echo $form->field($expendwithdrawals_model, "[{$index}]kaewithdr_id")->dropDownList(
                              ArrayHelper::map($kaewithdrawals, 'kaewithdr_id', 'kaewithdr_amount'),
                              ['prompt'=> Module::t('modules/finance/app', 'Assign Withdrawal')]);
        }
    ?>
	<hr />
	<h3><?= Module::t('modules/finance/app', 'Assign deductions');?></h3>
    <?php 
        foreach($expenddeduction_models as $index => $expenddeduction_model){
            echo $form->field($expenddeduction_model, "[{$index}]deduct_id")->dropDownList(
                              ArrayHelper::map($deductions, 'deduct_id', 'deduct_name'),
                              ['prompt'=> Module::t('modules/finance/app', 'Assign Deduction')]);
    }
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
