<?php

use app\modules\base\widgets\HeadSignature\models\HeadSignature;
use app\modules\disposal\DisposalModule;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="body-content">
	<div class="well">
    	<div class="row">
    		<div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Disposals for Approval');?></h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'Manage disposals under approval');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), Url::to(['/disposal/disposal']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
    		<div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Processed Disposals');?></h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'View approved disposals');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Approved Disposals'), Url::to(['/disposal/disposal', 'archived' => 1]), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
    		<div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Rejected Disposals');?></h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'View rejected disposals');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Rejected Disposals'), Url::to(['/disposal/disposal', 'rejected' => 1]), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>                            
      	</div>
      	<div class="row">
    		<div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Disposals Approvals');?></h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'Manage disposals approvals');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposals Approvals'), Url::to(['/disposal/disposal-approval']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>      	
            <div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Local Directorate Decisions');?></h3>
                <p><?= DisposalModule::t('modules/disposal/app', 'Manage decisions of local Directorates');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Local Directorate Decisions'), Url::to(['/disposal/disposal-localdirdecision']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
            <div class="col-lg-4">          
                <h3><?= DisposalModule::t('modules/disposal/app', 'Current Signature Adjustment');?></h3>
                <p><?= DisposalModule::t('modules/disposal/app', 'Your current adjustment for document signature is:') . '<h4><span class="label label-info">' . HeadSignature::getSigningName(Yii::$app->session[Yii::$app->controller->module->id . "_whosigns"]) . '</span></h4>';?></p>
            </div>
      	</div>
  	</div>
  	<div class="well">
        <div class="row">
            <div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Disposal Reasons');?></h3>
                <p><?= DisposalModule::t('modules/disposal/app', 'Manage the reasons for disposing a teacher');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Disposal Reasons'), Url::to(['/disposal/disposal-reason/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
            <div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Duties of Disposed Teachers');?></h3>
                <p><?= DisposalModule::t('modules/disposal/app', 'Manage the duties of a disposed teacher');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Duties of Disposed Teachers'), Url::to(['/disposal/disposal-workobj/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
            <div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Teachers');?></h3>
                <p><?= DisposalModule::t('modules/disposal/app', 'Teachers servicing in schools and services of Crete, as retrieved from MySchool');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Teachers'), Url::to(['/eduinventory/teacher/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
        </div>
    </div>
    <div class="well">
        <div class="row">
    		<div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Statistics');?></h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'View statistics of the approved disposals based on the selected parameters');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Statistics'), Url::to(['/disposal/disposal-statistic/index']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>   
        </div>
	</div>
	<div class="well">
        <div class="row">
    		<div class="col-lg-4">
                <h3><?= DisposalModule::t('modules/disposal/app', 'Help');?></h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'Help regarding the use of the disposals\' application');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Help using the application'), Url::to(['/disposal/default/help?helpId=1#disposalapp_help']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
    		<div class="col-lg-4">
                <h3>&nbsp;</h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'Help regarding the procedure for approving a disposal');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Help approving a disposal'), Url::to(['/disposal/default/help?helpId=2#disposal_help']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>
    		<div class="col-lg-4">
                <h3>&nbsp;</h3>
    			<p><?= DisposalModule::t('modules/disposal/app', 'Help regarding the available legislation related to disposals');?></p>
    			<p><?= Html::a(DisposalModule::t('modules/disposal/app', 'Legislation'), Url::to(['/disposal/default/help?helpId=3#legislation']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
            </div>            
        </div>
    </div>
</div>
