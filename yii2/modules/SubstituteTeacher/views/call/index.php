<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\CallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Calls');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="call-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Call'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'title',
            'year',
            'description:ntext',
            [
                'attribute' => 'application_start',
                'value' => function ($m) {
                    return \Yii::$app->formatter->asDate($m->application_start);
                },
                'filter' => false
            ],
            [
                'attribute' => 'application_end',
                'value' => function ($m) {
                    return \Yii::$app->formatter->asDate($m->application_end);
                },
                'filter' => false
            ],
            // 'created_at',
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

    ?>
</div>
