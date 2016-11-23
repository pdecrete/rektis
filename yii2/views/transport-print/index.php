<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransportPrintSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transport Prints');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-print-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'transport',
            [
                'attribute' => 'transport',
                'value' => 'transport0.information',
                'filter' => false
            ],          
            'filename',
            [
                'attribute' => 'create_ts',
                'filter' => false
            ],
            //'send_ts',
            // 'to_emails:email',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{show} {download}',
                'buttons' => [
                    'download' => function ($url, $model, $key) {
                        return Html::a(
                                    '<span class="glyphicon glyphicon-download"></span>', Url::to(['/transport/download', 'id' => $model->transport, 'printid' => $model->id]), [
                                    'title' => Yii::t('app', 'Download'),
                                    'data-confirm' => Yii::t('app', 'Are you sure you want to download this transport?'),
                                    'data-method' => 'post',
                                        ]
                        );
                    },
                        'show' => function ($url, $model, $key) {
                        return Html::a(
                                        '<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['/transport/view', 'id' => $model->transport]), [
                                    'title' => Yii::t('app', 'Transport'),
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
