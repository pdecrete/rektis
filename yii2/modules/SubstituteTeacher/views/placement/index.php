<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use app\modules\SubstituteTeacher\models\Call;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
use app\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\PlacementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Placements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Placement Decision'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'call_id',
                'value' => function ($model) {
                    return empty($model->call_id) ? null : $model->call->title;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'call_id',
                    'data' => Call::defaultSelectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            // 'date',
            [
                'attribute' => 'date',
                'value' => function ($m) {
                    return \Yii::$app->formatter->asDate($m->date);
                },
                'filter' => DateControl::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'layout' => '{remove}{input}'
                    ],
                ])
            ],
            'decision_board',
            'decision',
            // 'comments:ntext',
            'deleted:boolean',
            // 'created_at',
            // 'updated_at',

            [
                'class' => FilterActionColumn::className(),
                'filter' => FilterActionColumn::LINK_INDEX_CONFIRM,
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return $model->deleted != true;
                    },
                ],
                'contentOptions' => [
                    'class' => 'text-center text-nowrap'
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
