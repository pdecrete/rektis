<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditpercentage */

$this->title = $model->kaeperc_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaecreditpercentages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-kaecreditpercentage-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->kaeperc_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->kaeperc_id], [
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
            'kaeperc_id',
            'kaeperc_percentage',
            'kaeperc_date',
            'kaeperc_decision',
            'kaecredit_id',
        ],
    ]) ?>

</div>
