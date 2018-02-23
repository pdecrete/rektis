<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use kartik\datecontrol\DateControl;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-expenditure-form  col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($state_model, 'expstate_date')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE
	       ])->label(Module::t('modules/finance/app', 'Date'));
    ?>
    
	<?= $form->field($state_model, 'expstate_comment')->textInput(['maxlength' => true])->
	       label(Module::t('modules/finance/app', 'Description')); ?>
    
    <div class="form-group  text-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($state_model->isNewRecord ? Module::t('modules/finance/app', 'Forward Expenditure State') : Module::t('modules/finance/app', 'Update Expenditure State'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
