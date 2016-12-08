<?php

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Transport */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

$this->title = ($model->employee0 ? $model->employee0->fullname : Yii::t('app', 'UNKNOWN')) . ': ' . Yii::t('app', 'Journal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->information, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Print');
?>
<div class="transport-view">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="row">
		<div class="col-lg-5">	
			<?= Html::beginForm(['printjournal'], 'post', ['enctype' => 'multipart/form-data']) ?>
				<h2><?= Yii::t('app', 'Journal Dates') ?> </h2>
				<?=
					\yii\helpers\Html::hiddenInput('id', $model->id);
				?>	
				<?=		
					\yii\helpers\Html::hiddenInput('ftype', $ftype);
				?>	

				<label class="col-xs-4 col-sm-4 col-md-3 col-lg-3 control-label"><?= Yii::t('app', 'From')?> </label>
				<?= DatePicker::widget([
					'name' => 'from', 
					'value' => Yii::$app->formatter->asDate($model->start_date), //date('d/m/Y'),
					'options' => ['placeholder' => ' '],
					'pluginOptions' => [
						'format' => 'dd/mm/yyyy',
						'todayHighlight' => true
						]
					]);
				?>				

				<label class="col-xs-4 col-sm-4 col-md-3 col-lg-3 control-label"><?= Yii::t('app', 'To')?></label>
				<?= DatePicker::widget([
					'name' => 'to', 
					'value' => Yii::$app->formatter->asDate($model->end_date), //date('d/m/Y'),
					'options' => ['placeholder' => ' '],
					'pluginOptions' => [
						'format' => 'dd/mm/yyyy',
						'todayHighlight' => true
						]
					]);
				?>			
				<div class="form-group">
					<?= Html::a(Yii::t('app', 'Return'), ['print', 'id' => $model->id, 'ftype' => $ftype], ['class' => 'btn btn-primary']) ?>	
					<?= Html::submitButton(Yii::t('app', 'Continue'), ['class' => 'btn btn-success']) ?> 
				</div>
				<?= Html::endForm() ?>
		</div>
	</div>
</div>
