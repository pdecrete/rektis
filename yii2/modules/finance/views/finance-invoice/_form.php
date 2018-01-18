<?php

use app\modules\finance\Module;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-invoice-form">

	<div class="panel panel-default">
		<div class="panel-heading"><h4><?= Module::t('modules/finance/app', 'Voucher Details') ?></h4></div>
		<div class="panel-body">
			<p><strong><?= Module::t('modules/finance/app', 'Supplier') ?>: </strong><?= $supplier_model->suppl_name;?></p>
			<p><strong><?= Module::t('modules/finance/app', 'Amount') ?>: </strong><?= $expenditure_model->exp_amount;?></p>
		</div>
	</div>
	
    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($invoice_model, 'invtype_id')->dropDownList(
                    ArrayHelper::map($invoicetypes_model,'invtype_id', 'invtype_title'),
                                    ['prompt'=>Module::t('modules/finance/app', 'Voucher Type')])
    ?>
    
    <?= $form->field($invoice_model, 'inv_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($invoice_model, 'inv_order')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($invoice_model, 'inv_date')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($invoice_model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $invoice_model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
