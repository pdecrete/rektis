<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */

$this->title = $model->approval_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disposal Approvals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-approval-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->approval_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->approval_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'approval_regionaldirectprotocol',
            'approval_localdirectprotocol',
            'approval_notes',
            'approval_file',
            'approval_signedfile',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
