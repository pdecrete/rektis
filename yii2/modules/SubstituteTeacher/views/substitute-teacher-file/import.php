<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\SubstituteTeacherFileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $route string */
/* @var $type string */

$this->title = Yii::t('substituteteacher', 'Substitute Teacher Files');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="substitute-teacher-file-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                'template' => '{select}',
                'buttons' => [
                    'select' => function ($url, $model, $key) use ($route, $type) {
                        return Html::a(
                                '<span class="glyphicon glyphicon-check"></span> ' . Yii::t('substituteteacher', 'Select file'), Url::to([$route, 'type' => $type, 'file_id' => $model->id]), [
                                'class' => 'btn btn-sm btn-primary btn-block',
                                'title' => Yii::t('substituteteacher', 'Select for import'),
                                ]
                        );
                    },
                ]
            ],
        ],
    ]);

    ?>
</div>
