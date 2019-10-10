<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */

$this->title = DisposalModule::t('modules/disposal/app', 'Republish Approval');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Disposals Approvals-Decisions'), 'url' => ['/disposal/disposal-approval/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="disposal-approval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'disposals_models' => $disposals_models,
        'disposalapproval_models' => $disposalapproval_models,
        'teacher_models' => $teacher_models,
        'specialization_models' => $specialization_models,
        'selection' => 1,
        'republish' => 1,

        'disposal_hours' => $disposal_hours,
        'disposal_days' => $disposal_days,
        'disposal_reasons' => $disposal_reasons,
        'disposal_duties' => $disposal_duties,
        'schools' => $schools
    ]) ?>

</div>
