<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportProgram */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Schtransport Program',
]) . $model->program_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schtransport Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->program_id, 'url' => ['view', 'id' => $model->program_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schtransport-program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
