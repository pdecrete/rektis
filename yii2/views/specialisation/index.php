<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SpecialisationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Specialisations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="specialisation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Specialisation'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'name',
            [
                'class' => FilterActionColumn::className(),
                'filter' => FilterActionColumn::LINK_INDEX_CONFIRM,
                'visibleButtons' => [
                    'delete' => \Yii::$app->user->can('admin'),
                ],
            ],
        ],
    ]); ?>

</div>
