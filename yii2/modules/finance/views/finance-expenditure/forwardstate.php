<?php

use app\modules\finance\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures'), 'url' => ['/finance/finance-expenditure']];
$this->title = Module::t('modules/finance/app', 'Forward Expenditure State');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('/default/infopanel');?>

<div class="finance-expenditure-changestate">
	<h1><?php echo($this->title = Module::t('modules/finance/app', 'Forward Expenditure State to ') . 
	               '"' . $current_state_name . '"');  ?></h1>
	<?= $this->render('_forwardstateform', [
	    'state_model' => $state_model,
	    'current_state_name' => $current_state_name,
    ]) ?>
</div>