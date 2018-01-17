<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoice */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Invoice',
]) . $model->inv_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->inv_id, 'url' => ['view', 'id' => $model->inv_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
