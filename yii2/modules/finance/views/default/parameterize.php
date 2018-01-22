<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use app\modules\finance\Module;

/* @var $this yii\web\View */
$this->params['breadcrumbs'][] = ['label' =>  Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = Module::t('modules/finance/app', 'Parameters');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('/default/infopanel'); ?>
<h1><?= Module::t('modules/finance/app', 'Parameters');?></h1>

<div class="body-content">

    <div class="row">
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Suppliers');?></h3>
            <p><?= Module::t('modules/finance/app', 'Suppliers administration features (create new supplier, update, delete)');?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'View options'), Url::to(['/finance/finance-supplier']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Tax Office'); ?></h3>
                <p><?= Module::t('modules/finance/app', 'Create/Edit/Delete Tax Offices'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Administer Tax Offices'), Url::to(['/finance/finance-taxoffice']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'VAT')?></h3>
            <p><?= Module::t('modules/finance/app', 'Administer VAT options')?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'VAT Options'), Url::to(['/finance/finance-fpa']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Expenditure States')?></h3>
            <p><?= Module::t('modules/finance/app', 'Administer expenditure states')?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Expenditure States'), Url::to(['/finance/finance-state']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>    	
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Expenditure Deductions'); ?></h3>
            <p><?= Module::t('modules/finance/app', 'Administer deductions to be assigned to expenditures.'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Expenditure Deductions'), Url::to(['/finance/finance-deduction']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Voucher Types'); ?></h3>
            <p><?= Module::t('modules/finance/app', 'Administer types of vouchers.'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Voucher Types'), Url::to(['/finance/finance-invoicetype']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>            
	</div>
</div>
