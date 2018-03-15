<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\Teacher;
use app\models\Specialisation;
use app\modules\SubstituteTeacher\models\TeacherBoard;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherBoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teacher Boards');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="teacher-board-index">

        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <p>
            <?= Html::a(Yii::t('substituteteacher', 'Create Teacher Board'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'teacher_id',
                'value' => 'teacher.name',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'teacher_id',
                    'data' => Teacher::selectables('id', 'name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'specialisation_id',
                'value' => 'specialisation.label',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'specialisation_id',
                    'data' => Specialisation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'board_type',
                'value' => function ($model) {
                    return TeacherBoard::boardTypeLabel($model->board_type);
                },
                'filter' => TeacherBoard::getChoices('board_type')
            ],
            'points',
            'order',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
    </div>