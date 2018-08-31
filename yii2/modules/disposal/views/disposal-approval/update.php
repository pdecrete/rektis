<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Disposal Approval',
]) . $model->approval_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disposal Approvals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->approval_id, 'url' => ['view', 'id' => $model->approval_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="disposal-approval-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'disposals_models' => $disposals_models,
        'disposalapproval_models' => $disposalapproval_models
    ]) ?>

</div>
