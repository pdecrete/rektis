<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalWorkobj */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Disposal Workobj',
]) . $model->disposalworkobj_id;
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['/disposal/default']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Disposal Workobjs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->disposalworkobj_id, 'url' => ['view', 'id' => $model->disposalworkobj_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="disposal-workobj-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
