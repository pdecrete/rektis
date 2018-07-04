<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use app\modules\SubstituteTeacher\models\PlacementTeacherSearch;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$this->title = $model->decision;
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="placement-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= $model->deleted ? '' : Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('substituteteacher', 'Print summary and contract documents'), ['print', 'id' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to print the summary and contract documents? Previously printed documents will all be deleted.'),
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
                'value' => empty($model->call_id) ? null : $model->call->title
            ],
            'date:date',
            'decision_board',
            'decision',
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
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    ?>
    <?= $this->render('/placement-teacher/_index', compact('searchModel', 'dataProvider')) ?>
</div>