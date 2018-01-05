<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceInvoicetype */

$this->title = Yii::t('app', 'Create Finance Invoicetype');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Invoicetypes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-invoicetype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
