<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\KAE */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Kae',
]) . $model->kae_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kaes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kae_id, 'url' => ['view', 'id' => $model->kae_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kae-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
