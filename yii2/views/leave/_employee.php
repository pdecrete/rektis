<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>
<div class="leave-index">
    <h1>Ιστορικό αδειών <small>Σύνολο μη διεγραμμένων: <?= $employeeModel->leavesDuration; ?></small></h1>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'showFooter' => true,
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'type',
                'value' => 'typeObj.name',
                'footer' => '[ display ]',
            ],
            [
                'attribute' => 'duration',
                'value' => function ($model) {
                    return Yii::t('app', '{days} days, from {start_date} to {end_date}', [
                                'days' => $model->duration,
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
                            return Url::to(["/leave/{$action}", 'id' => $model->id]);
                        },
                                'buttons' => [
                                    'download' => function ($url, $model, $key) {
                                        return Html::a(
                                                        '<span class="glyphicon glyphicon-download"></span>', $url, [
                                                    'title' => Yii::t('app', 'Download'),
                                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to download this leave?'),
                                                    'data-method' => 'post',
//                                    'data-pjax' => '0',
                                                        ]
                                        );
                                    }
                                        ]
                                    ],
                                ],
                            ]);
                            ?>
                            <?php Pjax::end(); ?>
</div>
