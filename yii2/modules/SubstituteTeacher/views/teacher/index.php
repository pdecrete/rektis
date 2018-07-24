<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;
use kartik\select2\Select2;
use yii\bootstrap\ButtonDropdown;

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
        <?= Html::a(Yii::t('substituteteacher', 'Create Teacher'), ['create'], ['class' => 'btn btn-success pull-left', 'style' => 'margin-right: 0.5em;']) ?>
    </p>
    <?= ButtonDropdown::widget([
        'label' => Yii::t('substituteteacher', 'Batch Insert Teachers'),
        'options' => ['class' => 'btn-primary'],
        'dropdown' => [
            'items' => [
                [
                    'label' => Yii::t('substituteteacher', 'Batch insert teachers in Registry'),
                    'url' => ['substitute-teacher-file/import', 'route' => 'import/file-information', 'type' => 'registry']
                ],
                '<li class="divider"></li>',
                [
                    'label' => Yii::t('substituteteacher', 'Batch Insert Teacher In Year'),
                    'url' => ['substitute-teacher-file/import', 'route' => 'import/file-information', 'type' => 'teacher']
                ],
                [
                    'label' => Yii::t('substituteteacher', 'Download import sample'),
                    'url' => "{$bundle->baseUrl}/ΥΠΟΔΕΙΓΜΑ ΜΑΖΙΚΗΣ ΕΙΣΑΓΩΓΗΣ ΑΝΑΠΛΗΡΩΤΩΝ ΕΤΟΥΣ.xls"
                ],
            ],
        ],
    ]);
    ?>
    <p class="clearfix"></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if ($model->status == Teacher::TEACHER_STATUS_NEGATION) {
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
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
</div>