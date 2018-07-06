<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$this->title = $model->teacherBoard->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$positions_provider = new ArrayDataProvider(['allModels' => $model->placementPositions]);

?>
<div class="placement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= $model->deleted ? '' : Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= $model->altered ? '' : Html::a(Yii::t('substituteteacher', 'Alter'), ['alter', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to mark this placement as altered?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'teacher_board_id',
                'value' => $model->teacherBoard->teacher->name. ', ' . $model->teacherBoard->label
            ],
            [
                'attribute' => 'placement_id',
                'value' => empty($model->placement_id) ? null : $model->placement_id . ' (' . $model->placement->id . ')'
            ],
            'comments:ntext',
            'altered:boolean',
            'altered_at:datetime',
            'deleted:boolean',
            'deleted_at:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h2><?= Yii::t('substituteteacher', 'Placement') ?></h2>
    <?= GridView::widget([
        'dataProvider' => $positions_provider,
        'filterModel' => null,
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => [
                    'class' => 'text-center col-sm-1'
                ],
            ],
            'position.title',
            'teachers_count',
            'hours_count',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]); ?>

</div>
