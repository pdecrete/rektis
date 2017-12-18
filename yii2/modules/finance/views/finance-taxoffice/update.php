<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceTaxoffice */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Taxoffice',
]) . $model->taxoffice_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Taxoffices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->taxoffice_id, 'url' => ['view', 'id' => $model->taxoffice_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-taxoffice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
