<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transports');
$subtitle = Yii::t('app', 'Not deleted transports');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transport-index">

    <h1><?= Html::encode($this->title) ?> <small><?= Html::encode($subtitle) ?></small></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Transport'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'employee',
                'value' => 'employee0.fullname',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'employee',
                    'data' => \app\models\Employee::find()->innerJoin('admapp_specialisation', 'admapp_specialisation.id=admapp_employee.specialisation')->select(["CONCAT(admapp_employee.surname, \" \", admapp_employee.name, \" του \", admapp_employee.fathersname,  \" (\", admapp_specialisation.code, \")\") as fname", "admapp_employee.id"])->orderBy("fname")->indexBy("id")->column(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            [
                'attribute' => 'mode',
                'value' => 'mode0.name',
                'filter' => \app\models\TransportMode::find()->select(['name', 'id'])->indexBy('id')->column()
            ],
            // 'type',
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
            'decision_protocol',
            [
                'attribute' => 'decision_protocol_date',
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {printButton} {delete}',
                'buttons' => [
                    'printButton' => function ($url, $model, $key) {
                        return Html::a(yii\bootstrap\Html::icon('copy'), ['copy', 'id' => $model->id], ['title' => 'Νέα μετακίνημη με αντιγραφή']);
                    }
                ],
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return $model->locked === 1 ? false : true;
                    },
                    'delete' => function ($model, $key, $index) {
                        return $model->locked === 1 ? false : true;
                    }
                ],
                'contentOptions' => [
                    'class' => 'text-nowrap'
                ]
            ]
        ],
    ]);

    ?>
</div>
