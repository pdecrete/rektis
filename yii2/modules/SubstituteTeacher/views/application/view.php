<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Application */

$this->title = Yii::t('substituteteacher', 'Application') . " {$model->id}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Applications'), 'url' => empty($url = Url::previous('applicationsindex')) ? ['index'] : $url];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('substituteteacher', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'call_id',
                'value' => $model->call_id . ' (' . $model->call->title . ')'
            ],
            [
                'attribute' => 'teacher_board_id',
                'value' => $model->teacherBoard->teacher->name. ', ' . $model->teacherBoard->label
            ],
            'agreed_terms_ts:datetime',
            [
                'attribute' => 'state',
                'value' => $model->state_label,
                'format' => 'html'
            ],
            'reference:ntext',
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php 
    $provider = new ArrayDataProvider([
        'allModels' => $model->applicationPositions,
        // 'pagination' => [
        //     'pageSize' => 10,
        // ],
        // 'sort' => [
        //     'defaultOrder' => [
        //         'order' => SORT_DESC,
        //     ]
        // ],
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $provider,
        // 'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'contentOptions' => [
                    'class' => 'text-center col-sm-1'
                ],
            ],
            [
                'attribute' => 'order',
                'contentOptions' => [
                    'class' => 'text-center col-sm-1'
                ],
            ],
            [
                'header' => Yii::t('substituteteacher', 'In group'),
                'value' => function ($m) {
                    return $m->callPosition ? 
                        '<span class="label" style="color: #555; background-color: hsl('
                            . (($m->callPosition->group * 25 % 360) + 20) . ','
                            . (($m->callPosition->group == 0) ? '0%,90%' : '100%,50%') . ')">'
                            . (($m->callPosition->group == 0) ? Yii::t('substituteteacher', 'Sole position') : Yii::t('substituteteacher', 'Group {d}', ['d' => 1]))
                            . '</span>' :
                        null;
                },
                'contentOptions' => [
                    'class' => 'text-center col-sm-1'
                ],
                'format' => 'html'
            ],
            'callPosition.position.title',
            'deleted:boolean',
        ],
    ]); ?>

</div>