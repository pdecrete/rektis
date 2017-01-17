<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LeavePrintSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Leave Prints');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-print-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'leave',
            [
                'attribute' => 'leave',
                'value' => 'leaveObj.information',
                'filter' => false
            ],
            'filename',
            [
                'attribute' => 'create_ts',
                'filter' => false
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{show} {download}',
                'buttons' => [
                    'download' => function ($url, $model, $key) {
                        return Html::a(
                                        '<span class="glyphicon glyphicon-download"></span>', Url::to(['/leave/download', 'id' => $model->leave]), [
                                    'title' => Yii::t('app', 'Download'),
                                    'data-confirm' => Yii::t('app', 'Are you sure you want to download this leave?'),
                                    'data-method' => 'post',
                                        ]
                        );
                    },
                            'show' => function ($url, $model, $key) {
                        return Html::a(
                                        '<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/leave/view', 'id' => $model->leave]), [
                                    'title' => Yii::t('app', 'Leave'),
                                        ]
                        );
                    },
                        ]
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
</div>
