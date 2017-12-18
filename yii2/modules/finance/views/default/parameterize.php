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
                <p><?= Module::t('modules/finance/app', 'Create/Update/Delete Tax Offices'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Administer Tax Offices'), Url::to(['/finance/finance-taxoffice']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Expenditure State')?></h3>
            <p><?= Module::t('modules/finance/app', 'Set RCN credits for the <strong>currently, working</strong> year')?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Set RCN Credits'), Url::to(['/finance/finance-kaecredit']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>        
    </div>
	<div class="row">
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Expenditure Deduction'); ?></h3>
            <p><?= Module::t('modules/finance/app', 'Attribute percentages of initial RCNs credits of the currently working year'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Percentages'), Url::to(['/finance/finance-kaecreditpercentage']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>        
	</div>
</div>
