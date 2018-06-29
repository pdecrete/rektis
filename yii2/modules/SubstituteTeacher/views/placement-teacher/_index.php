<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\PlacementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="placement-index">
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
            'comments:ntext',
            'altered:boolean',
            'deleted:boolean',
            // 'created_at',
            // 'updated_at',

            [
                'label' => Yii::t('substituteteacher', 'Position placements'),
                'value' => function ($m) {
                    return ListView::widget([
                        'dataProvider' => new ArrayDataProvider(['allModels' => $m->placementPositions]),
                        'itemView' => '_position_list_item',
                        'summary' => ''
                    ]);
                },
                'format' => 'html'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {alter}',
                'urlCreator' => function ($action, $model, $key, $index, $actionColumn) {
                    $params = is_array($key) ? $key : ['id' => (string) $key];
                    $params[0] = 'placement-teacher/' . $action;
                    return Url::toRoute($params);
                },
                'buttons' => [
                    'alter' => function ($url, $model, $key) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-erase"></span>',
                                $url,
                                [
                                    'title' => Yii::t('substituteteacher', 'Mark this placement as altered'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('substituteteacher', 'Are you sure you want to mark this placement as altered?'),
                                    'class' => 'text-danger'
                                ]
                        );
                    },
                ],
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return $model->deleted != true;
                    },
                    'alter' => function ($model, $key, $index) {
                        return $model->altered != true;
                    },
                ],
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'white-space: nowrap'
                ]
            ],
        ],
    ]); ?>
</div>