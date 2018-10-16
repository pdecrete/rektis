<?php

use app\modules\disposal\DisposalModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\Disposal */

$url = 'index?archived=0'; 
$this->title = DisposalModule::t('modules/disposal/app', 'Import Disposals');
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Teachers\' Disposals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => DisposalModule::t('modules/disposal/app', 'Disposals for Approval'), 'url' => [$url]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disposal-import col-lg-6">
	<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
		<?= $form->field($import_model, 'importfile')->fileInput()->label(DisposalModule::t('modules/disposal/app', 'Disposals Excel File')) ?>
	    <div class="form-group  text-right">
        	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(DisposalModule::t('modules/disposal/app', 'Import Disposals'), ['class' => 'btn btn-primary']) ?>
    	</div>
	<?php ActiveForm::end(); ?>
</div>
