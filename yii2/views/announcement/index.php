<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AnnouncementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Announcements');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="announcement-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Announcement'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
//            'body:ntext',
//            'created_at',
            'updated_at',
            'deleted',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}{restore}',
                'buttons' => [
                    'restore' => function ($url, $model, $key) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-refresh"></span>', $url, [
                                'title' => Yii::t('app', 'Restore'),
                                'data-confirm' => Yii::t('app', 'Are you sure you want to restore this item?'),
                                'data-method' => 'post',
//                                    'data-pjax' => '0',
                                ]
                        );
                    }
                ],
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return $model->deleted == app\models\Announcement::ANNOUNCEMENT_ACTIVE;
                    },
                    'restore' => function ($model, $key, $index) {
                        return $model->deleted == app\models\Announcement::ANNOUNCEMENT_DELETED;
                    },
                ],
            ],
        ],
    ]);

    ?>
    <?php Pjax::end(); ?>
</div>
