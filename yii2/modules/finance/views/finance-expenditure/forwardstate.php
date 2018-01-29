<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use kartik\datecontrol\DateControl;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures'), 'url' => ['/finance/finance-expenditure']];
$this->title = Module::t('modules/finance/app', 'Forward Expenditure State');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('/default/infopanel');?>
<div class="finance-expenditure-form  col-lg-6">
	
	<h1><?php echo($this->title = Module::t('modules/finance/app', 'Forward Expenditure State to ') . 
	               '"' . $current_state_name . '"');  ?></h1>
    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($state_model, 'expstate_date')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE
	       ])->label(Module::t('modules/finance/app', 'Date'));
    ?>
    
	<?= $form->field($state_model, 'expstate_comment')->textInput(['maxlength' => true])->
	       label(Module::t('modules/finance/app', 'Description')); ?>
    
    <div class="form-group  text-right">
        <?= Html::submitButton(Module::t('modules/finance/app', 'Forward Expenditure State'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
