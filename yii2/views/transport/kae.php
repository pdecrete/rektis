<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Transport KAE sums');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-kae">
    <h1><?= Html::encode($this->title) ?></h1>
	<h1><small> Οι διεγραμμένες μετακινήσεις δεν λαμβάνονται υπόψη στον υπολογισμό.</small></h1>
	<?php					
		$count = \app\controllers\TransportController::getCountFundsTotals();
		$fundsSumDataProvider = \app\controllers\TransportController::getFundsTotals();
		$fundsSumDataProvider->totalCount = $count;
		$fundsSumDataProvider->pagination = [
				'pagesize' => 5, 
				'pageParam' => 'sumPage',
				];
		$fundsSumDataProvider->sort =[
				'attributes' => [
					'kae' => SORT_ASC,
					],
				'sortParam' => 'sumSort',
			];		
	?>
	<?php Pjax::begin(); ?>
	<?=
		GridView::widget([
			'dataProvider' => $fundsSumDataProvider,       
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],
				['label' => Yii::t('app', 'KAE'),
					'attribute' => 'kae'],
				['label' => Yii::t('app', 'In Amount'),
					'attribute' => 'inamount',
					'format' => 'Currency' ],
				['label' => Yii::t('app', 'Out Amount'),
					'attribute' => 'outamount',
					'format' => 'Currency' ],			
				['label' => Yii::t('app', 'Balance Sent'),
					'attribute' => 'balance',
					'format' => 'Currency' ],			
				['label' => Yii::t('app', 'Paid Amount'),
					'attribute' => 'paidamount',
					'format' => 'Currency' ],								
				['label' => Yii::t('app', 'Balance Paid'),
					'attribute' => 'balancepaid',
					'format' => 'Currency' ],			
			],	
		]);
	?>
	<?php Pjax::end(); ?>
</div>

