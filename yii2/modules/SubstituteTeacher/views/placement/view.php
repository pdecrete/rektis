<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use app\modules\SubstituteTeacher\models\PlacementTeacherSearch;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$placement_model_id = $model->id;
$this->title = $model->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placement decisions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$have_decision_prints = !empty($model->decisionPrints);
$have_any_prints = !empty($anyprint = $model->getPrints()->one());
$download_items = [];
if ($have_any_prints) {
    $download_items[] = '<li class="divider"></li>';
    $download_items[] = '<li class="dropdown-header"><i class="glyphicon glyphicon-download"></i> ' . Yii::t('substituteteacher', 'Download generated documents') . '</li>';
    $download_items[] = [
        'label' => Yii::t('substituteteacher', 'Download all documents (zip)'),
        'url' => ['download-all', 'id' => $model->id],
        'linkOptions' => [
            'data' => [
                'method' => 'post',
            ]
        ]
    ];
}
if ($have_decision_prints) {
    foreach ($model->decisionPrints as $dpmodel) {
        $download_items[] = [
            'label' => Yii::t('substituteteacher', 'Download placement decision document'),
            'url' => ['download-decision', 'id' => $model->id],
            'linkOptions' => [
                'data' => [
                    'method' => 'post',
                ]
            ]
        ];
    }
}
?>
<div class="placement-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group-container">
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= $model->deleted ? '' : Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= ButtonDropdown::widget([
            'label' => Yii::t('substituteteacher', 'Placement prints'),
            'options' => ['class' => 'btn-info'],
            'dropdown' => [
            'items' => array_merge([
                [
                    'label' => Yii::t('substituteteacher', 'Print summary and contract documents'),
                    'url' => ['print', 'id' => $model->id],
                    'linkOptions' => [
                        'data' => [
                            'confirm' => Yii::t('substituteteacher', 'Are you sure you want to print the summary and contract documents? Previously printed documents will all be deleted.'),
                            'method' => 'post',
                        ]
                    ]
                ],
                [
                    'label' => Yii::t('substituteteacher', 'Print placement decision document'),
                    'url' => ['print-decision', 'id' => $model->id],
                    'linkOptions' => [
                        'data' => [
                            'confirm' => Yii::t('substituteteacher', 'Are you sure you want to print the placement decision document? Any previously printed document will be deleted.'),
                            'method' => 'post',
                        ]
                    ]
                ],
            ], $download_items)
        ],
        ]);
        ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'call_id',
                'value' => empty($model->call_id) ? null : $model->call->title
            ],
            'date:date',
            'base_contract_start_date:date',
            'base_contract_end_date:date',
            'decision_board',
            'decision',
            'ada',
            'comments:ntext',
            'deleted:boolean',
            'deleted_at:datetime',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h3>
        <?= Yii::t('substituteteacher', 'Teacher placements') ?>
    </h3>
    <p>
        <?= Html::a(Html::icon('plus') . ' ' . Yii::t('substituteteacher', 'New teacher placement'), ['placement-teacher/create', 'placement_id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 
            $searchModel = new PlacementTeacherSearch();
            $params = Yii::$app->request->queryParams;
            $params['PlacementTeacherSearch']['placement_id'] = $model->id;
            $dataProvider = $searchModel->search($params);
    ?>
    <?= $this->render('/placement-teacher/_index', compact('searchModel', 'dataProvider', 'placement_model_id')) ?>
</div>