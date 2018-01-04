<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceDeduction */

$this->title = $model->deduct_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Deductions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-deduction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->deduct_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->deduct_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'deduct_id',
            'deduct_name',
            'deduct_description',
            'deduct_date',
            'deduct_percentage',
            'deduct_downlimit',
            'deduct_uplimit',
            'detuct_obsolete',
        ],
    ]) ?>

</div>
