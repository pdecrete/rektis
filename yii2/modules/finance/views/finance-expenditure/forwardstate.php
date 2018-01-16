<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use kartik\datecontrol\DateControl;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'View Expenditures'), 'url' => ['/finance/finance-expenditure']];
$this->title = Module::t('modules/finance/app', 'Forward Expenditure State');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="finance-expenditure-form">
	<?= $this->render('/default/infopanel');?>
	<h1><?= $this->title = Module::t('modules/finance/app', 'Forward Expenditure State'); ?></h1>
    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($state_model, 'expstate_date')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE
        ]);
    ?>
    
	<?= $form->field($state_model, 'expstate_comment')->textInput(['maxlength' => true]); ?>
    
    <div class="form-group  text-right">
        <?= Html::submitButton(Module::t('modules/finance/app', 'Forward State'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
