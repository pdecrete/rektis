<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->title = $model->kaecredit_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaecredits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-kaecredit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->kaecredit_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->kaecredit_id], [
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
            'kaecredit_id',
            'kaecredit_amount',
            'kaecredit_date',
            'kaecredit_updated',
            'year',
            'kae_id',
        ],
    ]) ?>

</div>
