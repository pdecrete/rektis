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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
    <h2><small>Επιλέξτε (κλικ αριστερά) τα αρχεία για τα οποία θέλετε να δημιουργηθεί διαβιβαστικό</small></h2>  
	<br>
	<?=Html::beginForm(['bulk'],'post');?>
		<?=Html::submitButton(Yii::t('app', 'Create new transport costs post'), ['class' => 'btn btn-success',]);?>
		<br><br>
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
	<?= Html::endForm();?> 
</div>
