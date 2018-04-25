<?php

use app\modules\schooltransport\Module;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Module::t('modules/schooltransport/app', 'School Transportations');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="body-content">

    <div class="row">
		<div class="col-lg-4">
            <h3><?= Module::t('modules/schooltransport/app', 'Transportations Approvals');?></h3>
			<p><?= Module::t('modules/schooltransport/app', 'View/create/edit/delete transportations');?></p>
			<p><?= Html::a(Module::t('modules/schooltransport/app', 'Transportations Approvals'), Url::to(['/schooltransport/schtransport-transport']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3><?= Module::t('modules/schooltransport/app', 'School Units');?></h3>
            <p><?= Module::t('modules/schooltransport/app', 'View school units as retrieved from myschool');?></p>
			<p><?= Html::a(Module::t('modules/schooltransport/app', 'School Units'), Url::to(['/schooltransport/schoolunit/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3><?= Module::t('modules/schooltransport/app', 'Transportation Approval States');?></h3>
            <p><?= Module::t('modules/schooltransport/app', 'View/edit states of a transportation approval');?></p>
			<p><?= Html::a(Module::t('modules/schooltransport/app', 'Transportation Approval States'), Url::to(['/schooltransport/schtransport-state']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>        
    </div>
</div>
