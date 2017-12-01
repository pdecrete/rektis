<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaecreditpercentageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Finance Kaecreditpercentages');
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>"; print_r($dataProvider->models); echo "</pre>";die();
?>
<div class="finance-kaecreditpercentage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Finance Kaecreditpercentage'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kae_id',
            'kae_title',
            'kaecredit_amount',
            'kaeperc_percentage',
            'kaeperc_date',
            'kaeperc_decision',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
