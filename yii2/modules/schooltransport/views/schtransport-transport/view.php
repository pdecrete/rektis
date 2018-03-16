<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */

$this->title = $model->transport_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Transports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-transport-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->transport_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->transport_id], [
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
            'transport_id',
            'transport_submissiondate',
            'transport_startdate',
            'transport_enddate',
            'transport_teachers',
            'transport_students',
            'meeting_id',
            'school_id',
        ],
    ]) ?>

</div>
