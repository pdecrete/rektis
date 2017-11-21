<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKae */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Kae',
]) . $model->kae_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kae_id, 'url' => ['view', 'id' => $model->kae_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-kae-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
