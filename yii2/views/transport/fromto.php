<?php

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\Transport */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
//use yii\helpers\Url;

$this->title = ($model->employee0 ? $model->employee0->fullname : Yii::t('app', 'UNKNOWN')) . ': ' . Yii::t('app', 'Journal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->information, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Print');
?>
<div class="transport-view">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="row">
		<div class="col-lg-5">	
			<?php //$form = ActiveForm::begin(['action' => 'printjournal', 'method' => 'post']); ?>
			<?= Html::beginForm(['printjournal'], 'post', ['enctype' => 'multipart/form-data']) ?>
				<h2><?= Yii::t('app', 'Journal Dates') ?> </h2>
				<?=
					\yii\helpers\Html::hiddenInput('id', $model->id);
					//$form->field($model, 'id',['inputOptions' => ['value' => $model->id]])->hiddenInput()->label(false);
				?>	
				<?=		
					\yii\helpers\Html::hiddenInput('ftype', $ftype);
					//$form->field($model, 'ftype',['inputOptions' => ['value' => $ftype]])->hiddenInput()->label(false);
				?>					
				<?= Html::label(Yii::t('app', 'From'), 'lbfrom') ?>
				<?=	
					\yii\helpers\Html::Input('date', 'from');
				?>
				<br>
				<?= Html::label(Yii::t('app', 'To'), 'lbto') ?>
				<?= 
					\yii\helpers\Html::Input('date', 'to'); 
				?>
				<div class="form-group">
					<?= Html::submitButton(Yii::t('app', 'Continue'), ['class' => 'btn btn-primary']) ?> 
				</div>
				<?= Html::endForm() ?>
			<?php //ActiveForm::end(); ?>
		</div>
	</div>
</div>
