<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            //'id',
            'name',
            'description:ntext',
            'limit',
            'reason_num', 
			[
				'label' => $model->getAttributeLabel('check'),
				'value' => Yii::t('app', '{boxstate}', [							
								'boxstate' => ($model->check == 1) ? Yii::t('app', 'YES') : Yii::t('app', 'NO'),
								])
			],
            [
                'label' => $model->getAttributeLabel('schoolyear_based'),
                'value' => function ($model) { return ($model->schoolyear_based == 1) ? 'Στο διδακτικό έτος' : 'Στο ημερολογιακό έτος';}
            ],
            'templatefilename',
            'create_ts',
            'update_ts',
        ],
    ]) ?>

</div>
