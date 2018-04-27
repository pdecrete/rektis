<?php

use yii\bootstrap\Html;
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'registry_id',
            'value' => 'registry.name',
        ],
        [
            'attribute' => '',
            'header' => Yii::t('substituteteacher', 'Teacher boards'),
            'value' => function ($m) {
                return $m->boards ? implode(
                    '<br>',
                    array_map(function ($model) {
                        return $model->label;
                    }, $m->boards)
                ) : null;
            },
            'format' => 'html'
        ],
        [
            'attribute' => '',
            'header' => Yii::t('substituteteacher', 'Placement preferences'),
            'value' => function ($m) {
                return $m->placementPreferences ? implode(
                    '<br>',
                    array_map(function ($pref) {
                        return $pref->label_for_teacher;
                    }, $m->placementPreferences)
                ) : null;
            },
            'filter' => false,
            'format' => 'html'
        ],
    ],
    'layout' => '{items}{summary}',
]);
