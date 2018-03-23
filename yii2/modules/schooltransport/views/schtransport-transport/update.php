<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportTransport */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Schtransport Transport',
]) . $model->transport_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Transports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->transport_id, 'url' => ['view', 'id' => $model->transport_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schtransport-transport-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'meeting_model' => $meeting_model,
        'program_model' => $program_model,
        'meetings' => $meetings,
        'schools' => $schools]) ?>
    ]) ?>

</div>
