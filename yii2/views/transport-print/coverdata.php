<?php

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Transport */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

$this->title = Yii::t('app', 'Transport Prints');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-print">
	<h1><?= Html::encode( Yii::t('app', 'Transport cover document print') ) ?></h1>
	<div class="row">
		<div class="col-lg-5">	
			<?= Html::beginForm(['printdata'], 'post', ['enctype' => 'multipart/form-data']) ?>
				<h2><?= Yii::t('app', 'Cover document data') ?> </h2>

				<?=	Html::hiddenInput('ftype', $ftype); ?>	
				
				<?= Html::hiddenInput('results0', $results[0]); ?>	
				<?= Html::hiddenInput('results1', $results[1]); ?>	
				<?= Html::hiddenInput('results2', $results[2]); ?>	
				<?= Html::hiddenInput('results3', $results[3]); ?>	
				<?= Html::hiddenInput('results4', $results[4]); ?>	
				<?= Html::hiddenInput('results5', $results[5]); ?>	
				<?= Html::hiddenInput('results6', $results[6]); ?>	
				<?= Html::hiddenInput('results7', $results[7]); ?>	
				<?= Html::hiddenInput('results8', $results[8]); ?>	
				<?= Html::hiddenInput('results9', $results[9]); ?>	
				<?= Html::hiddenInput('results10', $results[10]); ?>	
				<?= Html::hiddenInput('results11', $results[11]); ?>	
				<?= Html::hiddenInput('comma_separated', $comma_separated); ?>	
				<br>
				<p>
					<label class="col-xs-4 col-sm-4 col-md-3 col-lg-3 control-label"><?= Yii::t('app', 'Cover protocol')?> </label>
					<?=	Html::textInput('protocol'); ?>
				</p>
				<br>
				<p>
					<label class="col-xs-4 col-sm-4 col-md-3 col-lg-3 control-label"><?= Yii::t('app', 'Cover protocol date')?> </label>
					<?= DatePicker::widget([
						'name' => 'protocol_date', 
						'value' => Yii::$app->formatter->asDate(date('Y-m-d')),
						'options' => ['placeholder' => ' '],
						'pluginOptions' => [
							'format' => 'dd/mm/yyyy',
							'todayHighlight' => true
							]
						]);
					?>	
				</p>			
				<br>
				<p>
					<label class="col-xs-4 col-sm-4 col-md-3 col-lg-3 control-label"><?= Yii::t('app', 'Report num')?> </label>
					<?=	Html::textInput('rep_num', $results[11]); ?>
				</p>					
				<br>
				<p>
					<?= Yii::t('app', 'Total amount is (Euro): ') . '<b>' . number_format($results[5], 2 , ',', '') . '</b>. ' ?>
					<br>
					<label class="col-xs-4 col-sm-4 col-md-3 col-lg-3 control-label"><?= Yii::t('app', 'Amount in words') ?> </label>
					<?=	Html::textArea('whole_amount'); ?>
				</p>
				<div class="form-group">
					<?= Html::a(Yii::t('app', 'Return'), ['index', 'selected' => $comma_separated], ['class' => 'btn btn-primary']) ?>	
					<?= Html::submitButton(Yii::t('app', 'Continue'), ['class' => 'btn btn-success']) ?> 
				</div>
				<?= Html::endForm() ?>
		</div>
	</div>
</div>
