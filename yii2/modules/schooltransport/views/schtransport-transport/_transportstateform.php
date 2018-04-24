<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\schooltransport\Module;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-expenditure-form  col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($transportstate_model, 'transportstate_date')->widget(DateControl::classname(), 
                ['type' => DateControl::FORMAT_DATE])->label(Module::t('modules/schooltransport/app', 'Date'));
    ?>
                  
	<?= $form->field($transportstate_model, 'transportstate_comment')->textInput(['maxlength' => true])->
           label(Module::t('modules/schooltransport/app', 'Description')); ?>
    
    <div class="form-group  text-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($transportstate_model->isNewRecord ? Module::t('modules/schooltransport/app', 'Forward State') : Module::t('modules/schooltransport/app', 'Update State'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>