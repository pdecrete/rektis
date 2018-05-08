<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;

$bundle = \app\modules\SubstituteTeacher\assets\ModuleAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\PositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Positions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="position-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Position'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('substituteteacher', 'Import Positions'), ['substitute-teacher-file/import', 'route' => 'import/file-information', 'type' => 'position'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('substituteteacher', 'Download import sample'), "{$bundle->baseUrl}/ΥΠΟΔΕΙΓΜΑ ΜΑΖΙΚΗΣ ΕΙΣΑΓΩΓΗΣ ΚΕΝΩΝ.xls", ['class' => 'btn btn-default']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            [
                'attribute' => 'title',
                'value' => function ($m) {
                    return $m->title . $m->getSignLanguageLabelHtml();
                },
                'format' => 'html'
            ],
            [
                'attribute' => 'school_type',
                'value' => 'school_type_label',
                'label' => Yii::t('substituteteacher', 'Sch.Type'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'school_type',
                    'data' => \app\modules\SubstituteTeacher\models\Position::getSchoolTypeChoices(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'operation_id',
                'value' => 'operation.title',
//                'filter' => \app\modules\SubstituteTeacher\models\Operation::defaultSelectables(),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'operation_id',
                    'data' => \app\modules\SubstituteTeacher\models\Operation::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'specialisation_id',
                'value' => 'specialisation.code',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'specialisation_id',
                    'data' => \app\modules\SubstituteTeacher\models\Specialisation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'prefecture_id',
                'value' => 'prefecture.prefecture',
//                'filter' => app\modules\SubstituteTeacher\models\Prefecture::defaultSelectables(),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'prefecture_id',
                    'data' => app\modules\SubstituteTeacher\models\Prefecture::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'teachers_count',
                'filter' => false
            ],
//            [
//                'attribute' => 'covered_teachers_count',
//                'filter' => false
//            ],
            [
                'attribute' => 'hours_count',
                'filter' => false
            ],
//            [
//                'attribute' => 'covered_hours_count',
//                'filter' => false
//            ],
            // 'whole_teacher_hours',
            // 'created_at',
            // 'updated_at',
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]);

    ?>
</div>
