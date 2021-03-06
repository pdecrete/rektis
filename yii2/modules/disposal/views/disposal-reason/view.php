<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalReason */

$this->title = DisposalModule::t('modules/disposal/app', 'View Disposal Reason');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Disposal Reasons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-reason-view">
<h1><?= Html::encode($this->title) ?></h1>
    <p class="text-right">
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->disposalreason_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->disposalreason_id], [
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
            'disposalreason_id',
            'disposalreason_name',
            'disposalreason_description',
        ],
    ]) ?>
</div>