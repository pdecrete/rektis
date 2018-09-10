<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */

$this->title = DisposalModule::t('modules/disposal/app', 'Create Disposals\' Approval');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-approval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'disposals_models' => $disposals_models,
        'disposalapproval_models' => $disposalapproval_models,
        'teacher_models' => $teacher_models,
        'school_models' => $school_models,
        'specialization_models' => $specialization_models,
        'disposal_ids' => $disposal_ids,
        'selection' => 1
    ]) ?>

</div>