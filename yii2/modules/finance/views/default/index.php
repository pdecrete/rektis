<?php

use app\modules\finance\Module;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Module::t('modules/finance/app', 'Expenditures Management');
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('/default/infopanel'); ?>
<h1>Διαχείριση Δαπανών</h1>

<div class="body-content">

    <div class="row">
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Financial Year Administration');?></h3>
                <p><?= Module::t('modules/finance/app', 'Financial year administration features (new year, RCN credits attribution etc.)');?></p>
                    <p><?= Html::a(Module::t('modules/finance/app', 'View options'), Url::to(['/finance/default/administeryear']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Suppliers');?></h3>
            <p><?= Module::t('modules/finance/app', 'Suppliers administration features (create new supplier, update, delete)');?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'View options'), Url::to(['/finance/finance-supplier']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Expenditures');?></h3>
            <p><?= Module::t('modules/finance/app', 'Expenditures administration features (create, update, delete expenditure)');?></p>
        </div>        
    </div>

    <div class="row">
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Invoices');?></h3>
            <p><?= Module::t('modules/finance/app', 'Invoices administration (create new invoice, update details, delete)');?></p>
        </div>
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Reports');?></h3>
            <p><?= Module::t('modules/finance/app', 'Reports of expenditures, invoices, credit etc.');?></p>
        </div>     
    </div>
</div>
