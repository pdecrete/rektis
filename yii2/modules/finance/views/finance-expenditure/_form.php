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
    //echo "<pre>"; print_r($expenddeduction_models); echo "</pre>";
        echo $form->field($expenddeduction_models[0], '[0]deduct_id')->radioList(
        [
            $deductions[0]['deduct_id'] => $deductions[0]['deduct_name'],
            $deductions[1]['deduct_id'] => $deductions[1]['deduct_name'],
        ],
        ['separator'=>'<br/>']
        )->label(false);
    
        for($i = 1; $i < count($expenddeduction_models); $i++){
            echo $form->field($expenddeduction_models[$i], "[{$i}]deduct_id")->checkbox(['label' => $deductions[$i+1]->deduct_name, 'value' => $deductions[$i+1]->deduct_id]);
        }
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
