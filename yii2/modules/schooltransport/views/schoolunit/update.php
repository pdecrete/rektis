<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\Schoolunit */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Schoolunit',
]) . $model->school_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schoolunits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->school_id, 'url' => ['view', 'id' => $model->school_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schoolunit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
