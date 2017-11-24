<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\FinanceKaecreditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->title = Yii::t('app', 'Finance Kaecredits');
$this->params['breadcrumbs'][] = $this->title;

$provider = new ArrayDataProvider([
    'allModels' => $dataProvider,
    'pagination' => false,
    'sort' => ['attributes' => ['kae_id', 'kae_title', 'kaecredit_amount', 'kaecredit_date', 'kaecredit_updated']],
]);
?>
<div class="finance-kaecredit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Set RCN Credits'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget(   [  'dataProvider' => $provider,
	                           'columns' => [  
                                                'kae_id',
                                                'kae_title',
                                                'kaecredit_amount',
                                                'kaecredit_date',
	                                            'kaecredit_updated',
	                                           ]]);
    ?>
</div>
