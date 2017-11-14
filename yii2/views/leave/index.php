<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LeaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Leaves');
$subtitle = Yii::t('app', 'Not deleted leaves');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="leave-index">

    <h1><?= Html::encode($this->title) ?> <small><?= Html::encode($subtitle) ?></small></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Leave'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            [
                'attribute' => 'employee',
                'value' => 'employeeObj.fullname',
                'filter' => \app\models\Employee::find()->select(["CONCAT(surname, ' ', name) as fname", 'id'])->orderBy('fname')->indexBy('id')->column()
            ],
            [
                'attribute' => 'type',
                'value' => 'typeObj.name',
                'filter' => \app\models\LeaveType::find()->select(['name', 'id'])->indexBy('id')->column()
            ],
            [
                'attribute' => 'duration',
                'label' => \Yii::t('app', 'Days.SHORT')
            ],
            [
                'attribute' => 'start_date',
                'value' => function ($v) {
                    return \Yii::$app->formatter->asDate($v->start_date);
                },
                'filter' => DateControl::widget([
                    'model' => $searchModel,
                    'attribute' => 'start_date',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'layout' => '{remove}{input}'
                    ],
                ])
            ],
            [
                'attribute' => 'end_date',
                'value' => function ($v) {
                    return \Yii::$app->formatter->asDate($v->end_date);
                },
                'filter' => DateControl::widget([
                    'model' => $searchModel,
                    'attribute' => 'end_date',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'layout' => '{remove}{input}'
                    ],
                ])
            ],
            [
                'attribute' => 'decision_protocol',
                'label' => \Yii::t('app', 'decision_protocol.SHORT')
            ],
            [
                'attribute' => 'decision_protocol_date',
//                'format' => 'date',
                'label' => \Yii::t('app', 'decision_protocol_date.SHORT'),
                'value' => function ($v) {
                    return \Yii::$app->formatter->asDate($v->decision_protocol_date);
                },
                'filter' => DateControl::widget([
                    'model' => $searchModel,
                    'attribute' => 'decision_protocol_date',
                    'type' => DateControl::FORMAT_DATE,
                    'widgetOptions' => [
                        'layout' => '{remove}{input}'
                    ],
                ])
            ],
            // 'application_protocol',
            // 'application_protocol_date',
            // 'application_date',
            // 'accompanying_document',
            // 'reason',
            // 'comment:ntext',
            // 'create_ts',
            // 'update_ts',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {download}',
                'buttons' => [
                    'download' => function ($url, $model, $key) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-download"></span>', $url, [
                                'title' => Yii::t('app', 'Download'),
                                'data-confirm' => Yii::t('app', 'Are you sure you want to download this leave?'),
                                'data-method' => 'post',
//                                    'data-pjax' => '0',
                                ]
                        );
                    }
                ],
                'contentOptions' => [
                    'class' => 'text-nowrap'
                ]
            ],
        ],
    ]);

    ?>
    <?php Pjax::end(); ?></div>
