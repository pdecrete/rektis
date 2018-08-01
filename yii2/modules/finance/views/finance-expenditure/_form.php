<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use kartik\select2\Select2;
use app\modules\finance\components\Money;
use dosamigos\chartjs\ChartJs;
use app\modules\finance\models\FinanceDeduction;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */

//echo "<pre>"; print_r($deductions); echo "</pre>"; die();

$script = "$(document).on('click', '#button-add-another', function addFlatTax(){
                var basedivelemnt = document.getElementsByClassName('form-group field-financeexpenditure-flat_taxes-0')[0];
                var cloneelemnt = basedivelemnt.cloneNode(true);

                var baseelemnt = document.getElementById('financeexpenditure-flat_taxes-0');                
                var flattaxcounter = baseelemnt.getAttribute('flattaxcounter');
                var insertafter = document.getElementsByClassName('form-group field-financeexpenditure-flat_taxes-' + flattaxcounter)[0];

                flattaxcounter++;
                baseelemnt.setAttribute('flattaxcounter', flattaxcounter);

                var id = baseelemnt.id;
                var name = baseelemnt.name;
                var cloneclass = basedivelemnt.getAttribute('class');

                cloneclass = cloneclass.replace('0', flattaxcounter);

                id = id.replace('0', flattaxcounter);
                name = name.replace('0', flattaxcounter);
                cloneelemnt.setAttribute('class', cloneclass);                

                inputcloneelemnt = cloneelemnt.getElementsByTagName('input')[0];
                inputcloneelemnt.id = id;
                inputcloneelemnt.name = name;                

                insertafter.parentNode.insertBefore(cloneelemnt, insertafter.nextSibling);
                document.getElementById(id).value = 0;
           })";
$this->registerJs($script, View::POS_READY);


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
    
        $index = 0;
        $radiolist_array = array();
        $standard_deductions_ids = FinanceDeduction::getStandardFinanceDeductionsIds();
        foreach ($deductions as $index=>$deduction) {
          if(in_array($deduction['deduct_id'], $standard_deductions_ids)){
              $radiolist_array[$deductions[$index]['deduct_id']] = $deductions[$index]['deduct_name'] . ' (' . Money::toPercentage($deductions[$index]['deduct_percentage']) . ')';
          }
        }
        //echo "<pre>"; print_r($deductions); echo "</pre>"; //die();
        for ($i = 0; $i < count($deductions); $i++) {
            if(in_array($deductions[$i]['deduct_id'], $standard_deductions_ids)){
                array_splice($deductions, $i--, 1);
            }
        }

        echo $form->field($expenddeduction_models[0], '[0]deduct_id')->radioList($radiolist_array,['separator'=>'<br/>'])->label(false);        
        $index = 0;
        for ($i = 1; $i < count($expenddeduction_models); $i++, $index++) {            
            echo $form->field($expenddeduction_models[$i], "[{$i}]deduct_id")->checkbox(['label' => $deductions[$index]->deduct_name . ' (' . Money::toPercentage($deductions[$index]->deduct_percentage) . ')', 'value' => $deductions[$index]->deduct_id]);
        }
    ?>
    
	<hr />
	<h3><?= Module::t('modules/finance/app', 'Flat Taxes');?></h3>
	<p>
		<?php foreach ($model->flat_taxes as $index => $value):?>
        		<?= $form->field($model, 'flat_taxes[' . $index. ']')->textInput(['flattaxcounter' => count($model->flat_taxes)-1,'type' => 'number', 'min' => "0.00" ,
                                                                  'step' => '0.01',
                                                                  'style' => 'text-align: left',
        	                                                      'value' => $model->flat_taxes[$index]])->label(false);?>
		<?php endforeach;?>	                                                      
	</p>
    <p class="text-right"><?= Html::button('', ['id'=>'button-add-another', 'class'=>'glyphicon glyphicon-plus btn btn-primary btn-sm add-items']) ?></p>
	
	
    <?= $form->field($model, 'exp_notes')->textarea(['maxlength' => true]);
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
