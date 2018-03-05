<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\SubstituteTeacherFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Substitute Teacher Files');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="substitute-teacher-file-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Upload Substitute Teacher Files'), ['upload'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'title',
            'original_filename',
            'mime',
            // 'filename',
            [
                'attribute' => 'created_at',
                'filter' => false,
            ],
            // 'updated_at',
            // 'deleted',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {download} {update} {delete}',
                'buttons' => [
                    'download' => function ($url, $model, $key) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-download"></span>',
                            Url::to(['file-download', 'id' => $model->id]),
                            [
                                'title' => Yii::t('substituteteacher', 'Download'),
                                'data-confirm' => Yii::t('substituteteacher', 'Are you sure you want to download this file?'),
                                'data-method' => 'post',
                                ]
                        );
                    },
                ]
            ],
        ],
    ]);

    ?>
</div>
