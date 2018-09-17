<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecision */

$this->title = $model->localdirdecision_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/disposal/', 'Disposal Localdirdecisions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-localdirdecision-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app/modules/disposal/', 'Update'), ['update', 'id' => $model->localdirdecision_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/modules/disposal/', 'Delete'), ['delete', 'id' => $model->localdirdecision_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app/modules/disposal/', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'localdirdecision_id',
            'localdirdecision_protocol',
            'localdirdecision_subject',
            'localdirdecision_action',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'deleted',
            'archived',
        ],
    ]) ?>

</div>
