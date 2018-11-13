<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecision */

$this->title = DisposalModule::t('modules/disposal/app', 'View Local Directorate Decision');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Local Directorate Decisions'), 'url' => ['/disposal/disposal-localdirdecision']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-localdirdecision-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="pull-right">
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->localdirdecision_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->localdirdecision_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => DisposalModule::t('modules/disposal/app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'localdirdecision_id',
            'localdirdecision_protocol',
            'localdirdecision_subject',
            'localdirdecision_action',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',
            //'deleted',
            //'archived',
        ],
    ]) ?>

</div>
