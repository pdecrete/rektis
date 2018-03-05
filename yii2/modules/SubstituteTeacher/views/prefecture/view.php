<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Prefecture */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Prefectures'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prefecture-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'region',
            'prefecture',
        ],
    ]) ?>

</div>
