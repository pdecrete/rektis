<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportState */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Schtransport State',
]) . $model->state_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->state_id, 'url' => ['view', 'id' => $model->state_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schtransport-state-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
