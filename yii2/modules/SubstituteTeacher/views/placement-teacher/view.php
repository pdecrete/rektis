<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\bootstrap\ButtonDropdown;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$this->title = $model->teacherBoard->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placement decisions'), 'url' => ['placement/index']];
$this->params['breadcrumbs'][] = ['label' => $model->placement->label, 'url' => ['placement/view', 'id' => $model->placement_id]];
$this->params['breadcrumbs'][] = $this->title;

$positions_provider = new ArrayDataProvider(['allModels' => $model->placementPositions]);

?>
<div class="placement-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group-container">
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= ButtonDropdown::widget([
            'label' => Yii::t('substituteteacher', 'Quick mark placement'),
            'options' => ['class' => 'btn-info'],
            'dropdown' => [
                'items' => [
                    [
                        'label' => Yii::t('substituteteacher', 'Cancel placement'),
                        'url' => ['cancel', 'id' => $model->id],
                        'linkOptions' => [
                            'data' => [
                                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to mark this placement as cancelled? You must also update the placement to set the cancel decision number.'),
                                'method' => 'post',
                            ]
                        ]
                    ],
                    [
                        'label' => Yii::t('substituteteacher', 'Alter placement'),
                        'url' => ['alter', 'id' => $model->id],
                        'linkOptions' => [
                            'data' => [
                                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to mark this placement as altered?'),
                                'method' => 'post',
                            ]
                        ]
                    ],
                    [
                        'label' => Yii::t('substituteteacher', 'Dismiss teacher'),
                        'url' => ['dismiss', 'id' => $model->id],
                        'linkOptions' => [
                            'data' => [
                                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to mark this placement as dismissed? You must also update the placement to set the dismiss decision number.'),
                                'method' => 'post',
                            ]
                        ]
                    ],
                ],
            ]
        ]);
        ?>
    </div>

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
            'contract_start_date:date',
            'contract_end_date:date',
            'service_start_date:date',
            'service_end_date:date',
            'altered:boolean',
            'altered_at:datetime',
            'cancelled:boolean',
            'cancelled_at:datetime',
            [
                'attribute' => 'cancelled_ada',
                'value' => function ($model) {
                    if (empty($model->cancelled_ada)) {
                        return null;
                    } else {
                        return Html::a($model->cancelled_ada . Html::icon('link'), \Yii::$app->getModule('SubstituteTeacher')->params['ada-view-baseurl'] . urlencode($model->cancelled_ada), ['target' => '_blank']);
                    }
                },
                'format' => 'raw'
            ],
            'dismissed:boolean',
            'dismissed_at:datetime',
            [
                'attribute' => 'dismissed_ada',
                'value' => function ($model) {
                    if (empty($model->dismissed_ada)) {
                        return null;
                    } else {
                        return Html::a($model->dismissed_ada . Html::icon('link'), \Yii::$app->getModule('SubstituteTeacher')->params['ada-view-baseurl'] . urlencode($model->dismissed_ada), ['target' => '_blank']);
                    }
                },
                'format' => 'raw'
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h2>
        <?= Yii::t('substituteteacher', 'Placement') ?>
    </h2>
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
            'unified_hours_count',
            'teachers_count',
            'hours_count',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]); ?>

</div>