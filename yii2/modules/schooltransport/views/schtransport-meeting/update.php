<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportMeeting */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Schtransport Meeting',
]) . $model->meeting_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Meetings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->meeting_id, 'url' => ['view', 'id' => $model->meeting_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schtransport-meeting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
