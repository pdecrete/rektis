<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use app\modules\finance\Module;

/* @var $this yii\web\View */
$this->params['breadcrumbs'][] = ['label' =>  Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = 'Διαχείριση Οικονομικού Έτους';
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Module::t('modules/finance/app', 'Administer Financial Year');?></h1>

<div class="body-content">

    <div class="row">
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Year Settings')?></h3>
            <p><?= Module::t('modules/finance/app', 'Create/Update/Delete/Lock/Set Currently Working Year');?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Year Settings'), Url::to(['/finance/finance-year']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Administer RCN'); ?></h3>
                <p><?= Module::t('modules/finance/app', 'Create/Update/Delete RCN'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Administer RCN'), Url::to(['/finance/finance-kae']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
    </div>
	<div class="row">
        <div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'RCN Credits')?></h3>
            <p><?= Module::t('modules/finance/app', 'Set RCN credits for the <strong>currently, working</strong> year')?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Set RCN Credits'), Url::to(['/finance/finance-kaecredit']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Percentages of initial RCNs credits'); ?></h3>
            <p><?= Module::t('modules/finance/app', 'Attribute percentages of initial RCNs credits of the currently working year'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Percentages'), Url::to(['/finance/finance-kaecreditpercentage']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>        
		<div class="col-lg-4">
            <h3><?= Module::t('modules/finance/app', 'Withdrawals'); ?></h3>
            <p><?= Module::t('modules/finance/app', 'Create/Update/Delete Withdrawal from an RCN\'s credit'); ?></p>
            <p><?= Html::a(Module::t('modules/finance/app', 'Withdrawals'), Url::to(['/finance/finance-kaewithdrawal']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>            
        </div>
	</div>
</div>
