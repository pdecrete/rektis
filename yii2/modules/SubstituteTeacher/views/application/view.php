<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Application */

$this->title = Yii::t('substituteteacher', 'Application') . " {$model->id}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Applications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
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
            [
                'attribute' => 'call_id',
                'value' => $model->call_id . ' (' . $model->call->title . ')'
            ],
            [
                'attribute' => 'teacher_board_id',
                'value' => $model->teacherBoard->teacher->name. ', ' . $model->teacherBoard->label
            ],
            'agreed_terms_ts',
            [
                'attribute' => 'state',
                'value' => $model->state_label,
                'format' => 'html'
            ],
            'reference:ntext',
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php 
    $provider = new ArrayDataProvider([
        'allModels' => $model->applicationPositions,
        // 'pagination' => [
        //     'pageSize' => 10,
        // ],
        // 'sort' => [
        //     'defaultOrder' => [
        //         'order' => SORT_DESC,
        //     ]
        // ],
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $provider,
        // 'filterModel' => $searchModel,
        'columns' => [
            'id',
            'order',
            [
                'attribute' => 'call_position_id',
                'value' => 'callPosition.position.title',
            ],
            'deleted:boolean'
        ],
    ]); ?>

</div>