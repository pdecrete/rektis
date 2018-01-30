<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\Teacher;
use kartik\select2\Select2;
use app\modules\SubstituteTeacher\models\Prefecture;
use app\modules\SubstituteTeacher\models\PlacementPreference;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\PlacementPreferenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Placement Preferences');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-preference-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Placement Preference'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'teacher_id',
                'value' => function ($m) { return $m->teacher ? $m->teacher->name : null; },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'teacher_id',
                    'data' => Teacher::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'year',
                'value' => function ($m) { return $m->teacher ? $m->teacher->year : null; },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'year',
                    'data' => Teacher::defaultSelectables('year', 'year'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'prefecture_id',
                'value' => function ($m) { return $m->prefecture ? $m->prefecture->label : null; },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'prefecture_id',
                    'data' => Prefecture::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'school_type',
                'value' => 'school_type_label',
                'filter' => PlacementPreference::getChoices('school_type')
            ],
            'order',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
