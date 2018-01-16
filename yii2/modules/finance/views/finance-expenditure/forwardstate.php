<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use kartik\datecontrol\DateControl;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="finance-expenditure-form">

	<h4><?php 
	           echo Module::t('modules/finance/app', 'Expenditure Change State for supplier');
	           echo $supplier . "<hr />";
        ?> 
   </h4>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($state_model, 'expstate_comment')->textInput(['maxlength' => true]); ?>

	<?= $form->field($state_model, 'expstate_date')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE
        ]);
    ?>
    
    <div class="form-group">
        <?= Html::submitButton(Module::t('modules/finance/app', 'Forward State'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
