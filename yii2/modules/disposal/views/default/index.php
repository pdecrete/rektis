<?php

use app\modules\disposal\DisposalModule;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="body-content">
    	<div class="row">
    		<div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Disposals Approvals');?></h3>
			<p><?= DisposalModule::t('modules/disposal/app', 'View/create/edit/delete disposals');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals Approvals'), Url::to(['/disposal/disposal-approval']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
		<div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Disposals for Approval');?></h3>
			<p><?= DisposalModule::t('modules/disposal/app', 'View/create/edit/delete disposals');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), Url::to(['/disposal/disposal']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
		<div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Statistics');?></h3>
			<p><?= DisposalModule::t('modules/disposal/app', 'View statistics of the carried out school transportations based on the parameters selected.');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Statistics'), Url::to(['/disposal/statistic/index']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>   
    </div>
    <hr />
    <div class="row">
        <div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Disposal Reasons');?></h3>
            <p><?= DisposalModule::t('modules/disposal/app', 'View school units as retrieved from myschool');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposal Reasons'), Url::to(['/disposal/disposal-reason/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Duties of Disposed Teachers');?></h3>
            <p><?= DisposalModule::t('modules/disposal/app', 'View school units as retrieved from myschool');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Duties of Disposed Teachers'), Url::to(['/disposal/disposal-workobj/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Teachers');?></h3>
            <p><?= DisposalModule::t('modules/disposal/app', 'View school units as retrieved from myschool');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Teachers'), Url::to(['/teacher/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
    </div>
    <div class="row">    
        <div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Disposal Approval States');?></h3>
            <p><?= DisposalModule::t('modules/disposal/app', 'View/edit states of a transportation approval');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposal Approval States'), Url::to(['/disposal/disposal-state']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>    
    </div>
    <hr />
    <div class="row">
		<div class="col-lg-4">
            <h3><?= DisposalModule::t('modules/disposal/app', 'Help');?></h3>
			<p><?= DisposalModule::t('modules/disposal/app', 'Help regarding the application of the school transportations, the process of creating a school transportation approval as well as the related legislation.');?></p>
			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Help'), Url::to(['/disposal/default/help']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>      
    </div>    
</div>
