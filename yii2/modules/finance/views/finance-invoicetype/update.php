<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoicetype */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Invoicetype',
]) . $model->invtype_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Invoicetypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->invtype_id, 'url' => ['view', 'id' => $model->invtype_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-invoicetype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
