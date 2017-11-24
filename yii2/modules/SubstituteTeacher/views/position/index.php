<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;

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
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'title',
            [
                'attribute' => 'operation_id',
                'value' => 'operation.title',
//                'filter' => \app\modules\SubstituteTeacher\models\Operation::selectables(),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'operation_id',
                    'data' => \app\modules\SubstituteTeacher\models\Operation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'specialisation_id',
                'value' => 'specialisation.code',
//                'filter' => app\models\Specialisation::selectables(),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'specialisation_id',
                    'data' => app\models\Specialisation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'prefecture_id',
                'value' => 'prefecture.prefecture',
//                'filter' => app\modules\SubstituteTeacher\models\Prefecture::selectables(),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'prefecture_id',
                    'data' => app\modules\SubstituteTeacher\models\Prefecture::selectables(),
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

    ?>
</div>
