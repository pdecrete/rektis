<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
<div class="transport-index">
    <h1>Ιστορικό μετακινήσεων <small>Σύνολο μη διεγραμμένων: <?= $employeeModel->transportsDuration; ?> ημέρες</small></h1>
    <p>Διεγραμμένες μετακινήσεις επισημαίνονται με <span class="bg-danger">κόκκινο χρώμα</span>.</p>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) {
            if ($model->deleted) {
                return ['class' => 'danger'];
            }
        },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'type',
                        'value' => 'type0.name',
                    ],
                    [
                        'attribute' => 'duration',
                        'value' => function ($model) {
                            return Yii::t('app', '{days} days, from {start_date} to {end_date}', [
                                        'days' => $model->days_applied,
                                        'start_date' => \Yii::$app->formatter->asDate($model->start_date),
                                        'end_date' => \Yii::$app->formatter->asDate($model->end_date)
                            ]);
                        },
                            ],
                            'decision_protocol',
                            'decision_protocol_date:date',
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
                                'template' => '{view} {download}',
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    return Url::to(["/transport/{$action}", 'id' => $model->id]);
                                },
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return $model->deleted ? '' : Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                            'title' => Yii::t('yii', 'View'),
                                                            'data-pjax' => '0',
                                                ]);
                                            },
/*											'download' => function ($url, $model, $key) {
                                                return $model->deleted ? '' : Html::a(
                                                                '<span class="glyphicon glyphicon-download"></span>', $url, [
                                                            'title' => Yii::t('app', 'Download'),
                                                            'data-confirm' => Yii::t('yii', 'Are you sure you want to download this transport?'),
                                                            'data-method' => 'post',
                                                            'data-pjax' => '0',
                                                                ]
                                                );
                                            }
*/                                                ]
                                            ],
                                        ],
                                    ]);
                                    ?>
                                    <?php Pjax::end(); ?>
</div>
