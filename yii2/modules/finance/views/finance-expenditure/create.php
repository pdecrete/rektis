<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */

$this->title = Yii::t('app', 'Create Finance Expenditure');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Expenditures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-expenditure-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'vat_levels' => $vat_levels,
    ]) ?>

</div>
