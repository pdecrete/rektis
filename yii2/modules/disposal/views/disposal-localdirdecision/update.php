<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecision */

$this->title = Yii::t('app/modules/disposal/', 'Update {modelClass}: ', [
    'modelClass' => 'Disposal Localdirdecision',
]) . $model->localdirdecision_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/disposal/', 'Disposal Localdirdecisions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->localdirdecision_id, 'url' => ['view', 'id' => $model->localdirdecision_id]];
$this->params['breadcrumbs'][] = Yii::t('app/modules/disposal/', 'Update');
?>
<div class="disposal-localdirdecision-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
