<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransportFundsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transport Funds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-funds-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Transport Funds'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'date',
            'ada',
            //'service',
            [
                'attribute' => 'service',
                'value' => 'service0.name',
                'filter' => \app\models\Service::find()->select(['name', 'id'])->indexBy('id')->column()
            ],
            
            'code',
            'kae',
            'amount:currency',
		
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
