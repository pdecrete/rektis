<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use kartik\select2\Select2;
use app\modules\finance\components\Money;
use dosamigos\chartjs\ChartJs;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */

echo "<pre>"; print_r($deductions); echo "</pre>"; die();

$model->exp_amount = Money::toCurrency($model->exp_amount);
?>

<div class="finance-expenditure-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'exp_amount')->textInput(['maxlength' => true,
                                                        'type' => 'number',
                                                        'min' => "0.00" ,
                                                        'step' => '0.01',
                                                        'style' => 'text-align: left',
                                                        'value' => $model['exp_amount']]);
    ?>
    
    <?= $form->field($model, 'exp_description')->textInput(['maxlength' => true]);
    ?>

    <?= $form->field($model, 'suppl_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($suppliers, 'suppl_id', 'suppl_name'),
            'options' => ['placeholder' => Module::t('modules/finance/app', 'Select suppplier...')],
        ]);
    ?>

    <?= $form->field($model, 'fpa_value')->dropDownList(
        ArrayHelper::map($vat_levels, 'fpa_value', 'fpa_value'),
                                                        ['prompt' => Module::t('modules/finance/app', 'VAT'),
                                                         'value'  => Money::toPercentage($model->fpa_value, true)]
    );
    ?>
    
    <?= $form->field($model, 'exp_notes')->textarea(['maxlength' => true]);
    ?>
    
    <hr />
    <h3><?= Module::t('modules/finance/app', 'Assign withdrawals');?></h3>
    <?php 
        $i = 1;
        foreach ($expendwithdrawals_models as $index => $expendwithdrawals_model) {
            echo $form->field($expendwithdrawals_model, "[{$index}]kaewithdr_id")->dropDownList(
                              ArrayHelper::map($kaewithdrawals, 'kaewithdr_id', 'kaewithdr_amount'),
                              ['prompt' => Module::t('modules/finance/app', 'Assign Withdrawal')]
            )->label(false);
            echo $form->field($expendwithdrawals_model, "[{$index}]expwithdr_order")->hiddenInput(['value' => $i])->label(false);
            $i++;
        }
    ?>
    
	<hr />
	<h3><?= Module::t('modules/finance/app', 'Assign deductions');?></h3>
    <?php 

        //echo "<pre>"; print_r($expenddeduction_models); echo "</pre>"; die();

        $index = 0;

        echo $form->field($expenddeduction_models[0], '[0]deduct_id')->radioList(
        [
            $deductions[$index]['deduct_id'] => $deductions[0]['deduct_name'] . ' (' . Money::toPercentage($deductions[$index]['deduct_percentage']) . ')',
            $deductions[++$index]['deduct_id'] => $deductions[1]['deduct_name'] . ' (' . Money::toPercentage($deductions[$index]['deduct_percentage']) . ')',
            $deductions[++$index]['deduct_id'] => $deductions[2]['deduct_name'] . ' (' . Money::toPercentage($deductions[$index]['deduct_percentage']) . ')',
        ],
        ['separator'=>'<br/>']
        )->label(false);

        for ($i = 1; $i < count($expenddeduction_models); $i++) {
            ++$index;
            echo $form->field($expenddeduction_models[$i], "[{$i}]deduct_id")->checkbox(['label' => $deductions[$index]->deduct_name . ' (' . Money::toPercentage($deductions[$index]->deduct_percentage) . ')', 'value' => $deductions[$index]->deduct_id]);
        }
    ?>
    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>    	
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="col-lg-6">
	<?= $this->render('/default/kaewithdrawalsexpenditures', ['withdrawals_expendituressum' => $withdrawals_expendituressum, 'horizontal' => false]);?>			
</div>
