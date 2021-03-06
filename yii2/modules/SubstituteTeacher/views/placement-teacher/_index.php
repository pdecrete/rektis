<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\TeacherBoard;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;
use app\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\PlacementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="placement-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if (boolval($model->altered) || boolval($model->cancelled) || boolval($model->dismissed))  {
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'attribute' => 'teacher_board_id',
                'value' => function ($model) {
                    return Html::a(Html::icon('user'), ['teacher/view', 'id' => $model->teacherBoard->teacher_id], ['class' => 'btn btn-xs btn-default', 'title' => Yii::t('substituteteacher', 'View teacher entry')]) 
                        . ' ' . $model->teacherBoard->teacher->name. ', ' . $model->teacherBoard->label;
                },
                'filter' => false,
                // 'filter' => Select2::widget([
                //     'model' => $searchModel,
                //     'attribute' => 'teacher_board_id',
                //     'data' => TeacherBoard::selectablesWithTeacherInfo(),
                //     'theme' => Select2::THEME_BOOTSTRAP,
                //     'options' => ['placeholder' => '...'],
                //     'pluginOptions' => ['allowClear' => true],
                // ]),
                'format' => 'html'
            ],
            'comments:ntext',
            'cancelled:boolean',
            'altered:boolean',
            'dismissed:boolean',
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
                'class' => FilterActionColumn::className(),
                'filter' => Html::a(Html::icon('repeat'), ['placement/view', 'id' => $placement_model_id], ['class' => 'btn text-warning']),
                'template' => '{view} {update} {delete}<br>{cancel} {alter} {dismiss} {download-summary} {download-contract}',
                'urlCreator' => function ($action, $model, $key, $index, $actionColumn) {
                    $params = is_array($key) ? $key : ['id' => (string) $key];
                    $params[0] = 'placement-teacher/' . $action;
                    return Url::toRoute($params);
                },
                'buttons' => [
                    'cancel' => function ($url, $model, $key) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-remove-circle"></span>',
                                $url,
                                [
                                    'title' => Yii::t('substituteteacher', 'Mark this placement as cancelled'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('substituteteacher', 'Are you sure you want to mark this placement as cancelled? You must also update the placement to set the cancel decision number.'),
                                    'class' => 'text-danger'
                                ]
                        );
                    },
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
                    'dismiss' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-ban-circle text-danger"></span>',
                            $url,
                            [
                                'title' => Yii::t('substituteteacher', 'Mark teacher as dismissed.'),
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('substituteteacher', 'Are you sure you want to mark this placement as dismissed? You must also update the placement to set the dismiss decision number.')
                            ]
                        );
                    },
                    'download-summary' => function ($url, $model, $key) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-download"></span>',
                                ['placement/download-summary', 'id' => $model->id],
                                [
                                    'title' => Yii::t('substituteteacher', 'Download summary document'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('substituteteacher', 'Are you sure you want to download the summary document?'),
                                    'class' => 'text-primary'
                                ]
                        );
                    },
                    'download-contract' => function ($url, $model, $key) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-download-alt"></span>',
                                ['placement/download-contact', 'id' => $model->id],
                                [
                                    'title' => Yii::t('substituteteacher', 'Download contract document'),
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('substituteteacher', 'Are you sure you want to download the contract document?'),
                                    'class' => 'text-info'
                                ]
                        );
                    },
                ],
                'visibleButtons' => [
                    'cancel' => function ($model, $key, $index) {
                        return $model->cancelled != true;
                    },
                    'alter' => function ($model, $key, $index) {
                        return $model->altered != true;
                    },
                    'dismiss' => function ($model, $key, $index) {
                        return $model->altered != true && $model->dismissed != true;
                    },
                    'download-summary' => function ($model, $key, $index) {
                        return !empty($model->summaryPrints);
                    },
                    'download-contract' => function ($model, $key, $index) {
                        return !empty($model->contractPrints);
                    },
                ],
                'contentOptions' => [
                    'class' => 'text-center text-nowrap'
                ]
            ],
        ],
    ]); ?>
</div>