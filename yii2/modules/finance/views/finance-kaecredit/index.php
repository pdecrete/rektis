<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaecreditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Finance Kaecredits');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-kaecredit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Edit All Credits'), ['update'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'kaecredit_id',
            'kaecredit_amount',
            'kaecredit_date',
            'year',
            'kae_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
