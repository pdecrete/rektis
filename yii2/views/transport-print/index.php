<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\Transport;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransportPrintSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Transport Prints');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-print-index">

    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <h2><small>Επιλέξτε (κλικ αριστερά) τα αρχεία τα οποία θέλετε να επεξεργαστείτε</small></h2>  
	<p>'<b><u>Μεταφορά στα επιλεγμένα</u></b>' (μόνο ημερολόγια) για δημιουργία αποστολής πληρωμής<br>
	'<b><u>Πληρώθηκαν</u></b>' (μόνο συγκ. καταστάσεις ή διαβιβαστικά) για καταχώρηση πληρωμής από Υ.Δ.Ε. σε αποστολή<br>
	'<b><u>Αφαίρεση Πληρώθηκαν</u></b>' (μόνο συγκ. καταστάσεις ή διαβιβαστικά) για αφαίρεση καταχώρησης πληρωμής από Υ.Δ.Ε. σε αποστολή<br>
	</p>

	<?=Html::beginForm(['choose'],'post');?>

		<?= Html::hiddenInput('selected', $selected); ?>	
		 
		<?php Pjax::begin(); ?>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\CheckboxColumn'],
					[
						'attribute' => 'id',
						'visible' => false,
						'filter' => false
					],
					//'id',
					['class' => 'yii\grid\SerialColumn'],
					[
						'attribute' => 'transport',
						'value' => 'information',
						'filter' => false
					],
					[
						'attribute' => 'doctype',
						'value' => 'docname',
						'filter' => array(
										Transport::fapproval => Yii::t('app', 'Approvals'), 
										Transport::fjournal => Yii::t('app', 'Journals'), 
										Transport::fdocument => Yii::t('app', 'Covers'), 
										Transport::freport => Yii::t('app', 'Reports'),
									)
					],
					//'filename',
					'paid:boolean',
					'from:date',
					'to:date',
					//'create_ts',
					//'send_ts',
					// 'to_emails:email',
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{show} {download}',
						'buttons' => [
							'download' => function ($url, $model, $key) {
								return Html::a(
											'<span class="glyphicon glyphicon-download"></span>', Url::to(['download', 'id' => $model->id]), [
											'title' => Yii::t('app', 'Download'),
											'data-confirm' => Yii::t('app', 'Are you sure you want to download this transport?'),
											'data-method' => 'post',
												]
								);
							},
							]
						],
					],
				]);
			?>
		<?php Pjax::end(); ?>
	
	<?= Html::submitButton(Yii::t('app', 'Move to chosen'), ['class' => 'btn btn-success', 'name' => 'chosen',]);?>
	<?= Html::submitButton(Yii::t('app', 'Mark as paid'), ['class' => 'btn btn-info', 'name' => 'paid',]);?>
	<?= Html::submitButton(Yii::t('app', 'Mark as not paid'), ['class' => 'btn btn-warning', 'name' => 'unpaid',]);?>
	<?= Html::endForm();?> 
	
	
    <h1>Επιλογή για επεξεργασία</h1>   
	<p>'<b><u>Δημιουργία νέας αποστολής πληρωμής</u></b>' για δημιουργία αποστολής πληρωμής από τα επιλεγμένα ημερολόγια στον πίνακα που ακολουθεί<br>
	'<b><u>Αφαίρεση επιλεγμένων</u></b>' για να αφαιρέσετε τα επιλεγμένα ημερολόγια από τον πίνακα που ακολουθεί<br>
	</p>

	<br>		
	
	<?=Html::beginForm(['bulk'],'post');?>	

		<?= Html::hiddenInput('selected', $selected); ?>	
	
		<?php Pjax::begin(); ?>
			<?= GridView::widget([
				'dataProvider' => $choiceDataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\CheckboxColumn'],
					[
						'attribute' => 'id',
						'visible' => false,
						'filter' => false
					],
					//'id',
					['class' => 'yii\grid\SerialColumn'],
					[
						'attribute' => 'transport',
						'value' => 'information',
						'filter' => false
					],
					[
						'attribute' => 'doctype',
						'value' => 'docname',
						'filter' => array(
										Transport::fapproval => Yii::t('app', 'Approvals'), 
										Transport::fjournal => Yii::t('app', 'Journals'), 
										Transport::fdocument => Yii::t('app', 'Covers'), 
										Transport::freport => Yii::t('app', 'Reports'),
									)
					],
					//'filename',
					'from:date',
					'to:date',
					//'create_ts',
					//'send_ts',
					// 'to_emails:email',
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{show} {download}',
						'buttons' => [
							'download' => function ($url, $model, $key) {
								return Html::a(
											'<span class="glyphicon glyphicon-download"></span>', Url::to(['download', 'id' => $model->id]), [
											'title' => Yii::t('app', 'Download'),
											'data-confirm' => Yii::t('app', 'Are you sure you want to download this transport?'),
											'data-method' => 'post',
												]
								);
							},
							]
						],
					],
				]);
			?>
		<?php Pjax::end(); ?>

	<?=Html::submitButton(Yii::t('app', 'Create new transport costs post'), ['class' => 'btn btn-success', 'name' => 'createdocs',]);?>
	<?=Html::submitButton(Yii::t('app', 'Remove Selected'), ['class' => 'btn btn-warning', 'name' => 'remove', ]); ?>

	<?= Html::endForm();?> 
	
</div>
