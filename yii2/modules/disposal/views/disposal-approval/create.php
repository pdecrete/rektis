<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */

$this->title = Yii::t('app', 'Create Disposal Approval');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disposal Approvals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-approval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'disposals_models' => $disposals_models,
        'disposalapproval_models' => $disposalapproval_models
    ]) ?>

</div>
