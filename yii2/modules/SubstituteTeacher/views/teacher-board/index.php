<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\Specialisation;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\components\FilterActionColumn;

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
            <?= Html::a(Yii::t('substituteteacher', 'Teacher boards overview'), ['overview'], ['class' => 'btn btn-primary']) ?>
        </p>

        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if (($model->status == Teacher::TEACHER_STATUS_NEGATION) || ($model->status == Teacher::TEACHER_STATUS_DISMISSED) || ($model->status == Teacher::TEACHER_STATUS_CANCELLED)) {
                return ['class' => 'danger'];
            } elseif ($model->status == Teacher::TEACHER_STATUS_APPOINTED) {
                return ['class' => 'success'];
            } elseif ($model->status == Teacher::TEACHER_STATUS_PENDING) {
                return ['class' => 'warning'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'year',
                'value' => function ($m) {
                    return $m->teacher ? $m->teacher->year : null;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'year',
                    'data' => Teacher::selectables('year', 'year', null, function ($aq) {
                        return $aq->orderBy(['year' => SORT_DESC]);
                    }),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
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
                'attribute' => 'status',
                'value' => function ($model) {
                    return Teacher::statusLabel($model->status);
                },
                'filter' => Teacher::getChoices('status')
            ],

            [
                'class' => FilterActionColumn::className(),
                'filter' => FilterActionColumn::LINK_INDEX_CONFIRM,
                'template' => '{update} {delete}<br>{appoint} {negate} {eligible} {dismiss}',
                'contentOptions' => [
                    'class' => 'text-center text-nowrap'
                ],
                'buttons' => [
                    'appoint' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-ok-sign text-success"></span>',
                            $url,
                            ['title' => Yii::t('substituteteacher', 'Mark teacher as appointed.'), 'data-method' => 'post', 'data-confirm' => Yii::t('substituteteacher', 'This will change the board status but it will not modify any placement information.') ]
                        );
                    },
                    'negate' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-remove-sign text-danger"></span>',
                            $url,
                            [ 'title' => Yii::t('substituteteacher', 'Mark teacher as negated.'), 'data-method' => 'post', 'data-confirm' => Yii::t('substituteteacher', 'This will change the board status but it will not modify any placement information.') ]
                        );
                    },
                    'eligible' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-refresh text-info"></span>',
                            $url,
                            [ 'title' => Yii::t('substituteteacher', 'Mark teacher as eligible.'), 'data-method' => 'post', 'data-confirm' => Yii::t('substituteteacher', 'This will change the board status but it will not modify any placement information.') ]
                        );
                    },
                    'dismiss' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-ban-circle text-danger"></span>',
                            $url,
                            [ 'title' => Yii::t('substituteteacher', 'Mark teacher as dismissed.'), 'data-method' => 'post', 'data-confirm' => Yii::t('substituteteacher', 'This will change the board status but it will not modify any placement information.') ]
                        );
                    }
                ],
                'visibleButtons' => [
                    'appoint' => function ($model, $key, $index) {
                        return $model->status != Teacher::TEACHER_STATUS_APPOINTED;
                    },
                    'negate' => function ($model, $key, $index) {
                        return $model->status != Teacher::TEACHER_STATUS_NEGATION;
                    },
                    'eligible' => function ($model, $key, $index) {
                        return $model->status != Teacher::TEACHER_STATUS_ELIGIBLE;
                    },
                    'dismiss' => function ($model, $key, $index) {
                        return $model->status == Teacher::TEACHER_STATUS_APPOINTED;
                    }
                ]
            ],
        ],
    ]); ?>
    </div>