<?php

use app\modules\finance\Module;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-invoice-form col-lg-6">

	<div class="panel panel-default">
		<div class="panel-heading"><h4><?= Module::t('modules/finance/app', 'Voucher Details') ?></h4></div>
		<div class="panel-body">
			<p><strong><?= Module::t('modules/finance/app', 'Supplier') ?>: </strong><?= $supplier_model->suppl_name;?></p>
			<p><strong><?= Module::t('modules/finance/app', 'Amount') ?>: </strong><?= Money::toCurrency($expenditure_model->exp_amount, true);?></p>
		</div>
	</div>
	
    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($invoice_model, 'invtype_id')->dropDownList(
                    ArrayHelper::map($invoicetypes_model,'invtype_id', 'invtype_title'),
                                    ['prompt'=>Module::t('modules/finance/app', '---')])->
                                    label(Module::t('modules/finance/app', 'Voucher Type'));
    ?>
    
    <?= $form->field($invoice_model, 'inv_number')->textInput(['maxlength' => true])->
                label(Module::t('modules/finance/app', 'Number')); ?>

    <?= $form->field($invoice_model, 'inv_order')->textInput(['maxlength' => true])->
                label(Module::t('modules/finance/app', 'Order')); ?>
    
    <?= $form->field($invoice_model, 'inv_date')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE])->
                label(Module::t('modules/finance/app', 'Date')); ?>

    <div class="form-group text-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($invoice_model->isNewRecord ? Module::t('modules/finance/app', 'Create') : Module::t('modules/finance/app', 'Update'), ['class' => $invoice_model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>    	
    </div>

    <?php ActiveForm::end(); ?>

</div>
