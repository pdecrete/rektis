<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\PlacementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Placements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Placement'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'teacher_board_id',
                'value' => function ($model) {
                    return $model->teacherBoard->teacher->name. ', ' . $model->teacherBoard->label;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'teacher_board_id',
                    'data' => TeacherBoard::selectablesWithTeacherInfo(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
                'format' => 'html'
            ],
            [
                'attribute' => 'call_id',
                'value' => function ($model) {
                    return empty($model->call_id) ? null : $model->call_id . ' (' . $model->call->title . ')';
                }
            ],
            'date',
            // 'decision_board',
            'decision',
            // 'comments:ntext',
            'deleted:boolean',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
