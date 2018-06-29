<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;
use kartik\select2\Select2;

$bundle = \app\modules\SubstituteTeacher\assets\ModuleAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teachers');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="teacher-index">
        <h1>
            <?= Html::encode($this->title) ?>
        </h1>

        <p>
            <?= Html::a(Yii::t('substituteteacher', 'Create Teacher'), ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('substituteteacher', 'Batch Insert Teacher In Year'), ['substitute-teacher-file/import', 'route' => 'import/file-information', 'type' => 'teacher'], ['class' => 'btn btn-primary']) ?>

            <?= Html::a(Yii::t('substituteteacher', 'Batch Insert Teachers'), ['substitute-teacher-file/import', 'route' => 'import/file-information', 'type' => 'registry'], ['class' => 'btn btn-primary']) ?>

            <?= Html::a(Yii::t('substituteteacher', 'Download import sample'), "{$bundle->baseUrl}/ΥΠΟΔΕΙΓΜΑ ΜΑΖΙΚΗΣ ΕΙΣΑΓΩΓΗΣ ΑΝΑΠΛΗΡΩΤΩΝ ΕΤΟΥΣ.xls", ['class' => 'btn btn-default']) ?>
        </p>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if ($model->status == Teacher::TEACHER_STATUS_NEGATION) {
                return ['class' => 'danger'];
            } elseif ($model->status == Teacher::TEACHER_STATUS_APPOINTED) {
                return ['class' => 'success'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'registry_id',
                'value' => 'registry.name',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'registry_id',
                    'data' => TeacherRegistry::selectables('id', 'name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'year',
            [
                'attribute' => 'status',
                'value' => 'status_label',
                'filter' => Teacher::getChoices('status')
            ],
            [
                'attribute' => '',
                'header' => Yii::t('substituteteacher', 'Teacher boards'),
                'value' => function ($m) {
                    return $m->boards ? implode(
                        '<br>',
                        array_map(function ($model) {
                            return $model->label;
                        }, $m->boards)
                    ) : null;
                },
                'format' => 'html'
            ],
            [
                'attribute' => '',
                'header' => Yii::t('substituteteacher', 'Placement preferences'),
                'value' => function ($m) {
                    return $m->placementPreferences ? implode(
                        '<br>',
                        array_map(function ($pref) {
                            return $pref->label_for_teacher;
                        }, $m->placementPreferences)
                    ) : null;
                },
                'filter' => false,
                'format' => 'html'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {appoint} {negate} {eligible}',
                'buttons' => [
                    'appoint' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-ok-sign text-success"></span>',
                            $url,
                            ['title' => Yii::t('substituteteacher', 'Mark teacher as appointed.'), 'data-method' => 'post' ]
                        );
                    },
                    'negate' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-remove-sign text-danger"></span>',
                            $url,
                            [ 'title' => Yii::t('substituteteacher', 'Mark teacher as negated.'), 'data-method' => 'post' ]
                        );
                    },
                    'eligible' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-refresh text-info"></span>',
                            $url,
                            [ 'title' => Yii::t('substituteteacher', 'Mark teacher as eligible.'), 'data-method' => 'post' ]
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
                ]
            ],
        ],
    ]); ?>
    </div>