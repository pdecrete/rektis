<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use app\modules\SubstituteTeacher\models\Teacher;
use app\modules\SubstituteTeacher\models\Specialisation;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherBoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teacher boards overview');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-board-index">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="well well-sm teacher-board-form">
        <?php $form = ActiveForm::begin([
                'method' => 'GET'
            ]); ?>
        <div class="row">
            <div class="col-md-2">
                <label class="control-label">
                    <?= $searchModel->getAttributeLabel('year') ?>
                </label>
                <?= Select2::widget([
                    'name' => 'year',
                    'value' => $year,
                    'data' => Teacher::selectables('year', 'year', null, function ($aq) {
                        return $aq->orderBy(['year' => SORT_DESC]);
                    }),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
                    'pluginOptions' => ['allowClear' => true],
                ]);
                ?>
            </div>
            <div class="col-md-4">
                <label class="control-label">
                    <?= $searchModel->getAttributeLabel('specialisation_id') ?>
                </label>
                <?= Select2::widget([
                    'name' => 'specialisation',
                    'value' => $specialisation,
                    'data' => Specialisation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
                    'pluginOptions' => ['allowClear' => true],
                ]);
                ?>
            </div>
            <div class="col-md-4">
            <label class="control-label">
                    <?= $searchModel->getAttributeLabel('board_type') ?>
                </label>
                <?= Select2::widget([
                    'name' => 'board_type',
                    'value' => $board_type,
                    'data' => TeacherBoard::getChoices('board_type'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => Yii::t('substituteteacher', 'Choose...')],
                    'pluginOptions' => ['allowClear' => true],
                ]);
                ?>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <?= Html::submitButton(Html::icon('check') . ' ' . Yii::t('substituteteacher', 'Apply filter'), ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <?php if (empty($year) || empty($specialisation) || empty($board_type)) : ?>
        <p class="text-danger">Επιλέξτε έτος, ειδικότητα και τύπο πίνακα για καλύτερη διαλογή των αποτελεσμάτων!</p>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterUrl' => [
                    'overview',
                    'year' => $searchModel->year,
                    'specialisation' => $searchModel->specialisation_id,
                    'board_type' => $searchModel->board_type,
                ],
                'rowOptions' => function ($model, $key, $index, $grid) {
                    if (($model->status == Teacher::TEACHER_STATUS_NEGATION) || ($model->status == Teacher::TEACHER_STATUS_DISMISSED)) {
                        return ['class' => 'danger'];
                    } elseif ($model->status == Teacher::TEACHER_STATUS_APPOINTED) {
                        return ['class' => 'success'];
                    } elseif ($model->status == Teacher::TEACHER_STATUS_PENDING) {
                        return ['class' => 'warning'];
                    }
                },
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
                    // 'id',
                    'order',
                    'points',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return Teacher::statusLabel($model->status);
                        },
                        'filter' => Teacher::getChoices('status')
                    ],

                    [
                        'attribute' => 'year',
                        'value' => function ($m) {
                            return $m->teacher ? $m->teacher->year : null;
                        },
                        'filter' => false,
                        'visible' => empty($year)
                    ],
                    [
                        'attribute' => 'specialisation_id',
                        'value' => 'specialisation.label',
                        'filter' => false,
                        'visible' => empty($specialisation)
                    ],
                    [
                        'attribute' => 'board_type',
                        'value' => function ($model) {
                            return TeacherBoard::boardTypeLabel($model->board_type);
                        },
                        'filter' => false,
                        'visible' => empty($board_type)
                    ],

                    [
                        'attribute' => '',
                        'label' => Yii::t('substituteteacher', 'Placement preferences'),
                        'headerOptions' => [
                            'style' => 'white-space: wrap;'
                        ],
                        'value' => function ($model) {
                            return $model->teacher->placementPreferences ? implode(
                                ', ',
                                    array_map(function ($pref) {
                                        return $pref->label_short;
                                    }, $model->teacher->placementPreferences)
                                ) : null;        
                        },
                        'format' => 'html'
                    ],

                    'teacherRegistry.surname',
                    'teacherRegistry.firstname',
                    'teacherRegistry.fathername',
                    'teacherRegistry.mothername',
                    'teacherRegistry.mobile_phone',
                    'teacherRegistry.home_phone',
                    'teacherRegistry.home_address',
                    'teacherRegistry.tax_identification_number',
                    'teacherRegistry.tax_service',
                    'teacherRegistry.email',
                    'teacherRegistry.identity_number',
                    'teacherRegistry.birthplace',
                    'teacherRegistry.birthdate',

                ],
            ]);
            ?>
    </div>
</div>