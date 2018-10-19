<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\OperationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Operations');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('substituteteacher', 'Create Operation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'year',
            'title',
            [
                'attribute' => 'specialisation_labels',
                'format' => 'html'
            ],
            // 'description',
            // 'created_at',
            // 'updated_at',
            [
                'class' => FilterActionColumn::className(),
                'filter' => FilterActionColumn::LINK_INDEX_CONFIRM
            ],
        ],
    ]);

    ?>
</div>
