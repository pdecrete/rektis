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
            <h3><?= Module::t('modules/schooltransport/app', 'View Transportations');?></h3>
			<p><?= Module::t('modules/schooltransport/app', 'View/edit/delete transportations');?></p>
			<p><?= Html::a(Module::t('modules/schooltransport/app', 'View options'), Url::to(['/schooltransport/schtransport-transport']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
		<div class="col-lg-4">
            <h3><?= Module::t('modules/schooltransport/app', 'Create Transportation');?></h3>
            <p><?= Module::t('modules/schooltransport/app', 'Create a new transportation for a school unit');?></p>
			<p><?= Html::a(Module::t('modules/schooltransport/app', 'Expenditures'), Url::to(['/schooltransport/schtransport-transport/create']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>
        <div class="col-lg-4">
            <h3><?= Module::t('modules/schooltransport/app', 'School Units');?></h3>
            <p><?= Module::t('modules/schooltransport/app', 'View/edit/delete school units');?></p>
			<p><?= Html::a(Module::t('modules/schooltransport/app', 'Vouchers'), Url::to(['/schooltransport/schoolunit/']), ['class' => 'btn btn-primary', 'data-method' => 'post']) ?></p>
        </div>         
    </div>
</div>
