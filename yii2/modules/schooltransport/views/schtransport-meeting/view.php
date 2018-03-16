<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportMeeting */

$this->title = $model->meeting_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Meetings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-meeting-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->meeting_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->meeting_id], [
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
            'meeting_id',
            'meeting_city',
            'meeting_country',
            'meeting_startdate',
            'meeting_enddate',
            'program_id',
        ],
    ]) ?>

</div>
