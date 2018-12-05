<?php

use app\modules\eduinventory\EducationInventoryModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\Disposal */

$this->title = EducationInventoryModule::t('modules/eduinventory/app', 'Import Teachers');
$this->params['breadcrumbs'][] = ['label' => EducationInventoryModule::t('modules/eduinventory/app', 'Educational Data'), 'url' => ['/eduinventory']];
$this->params['breadcrumbs'][] = ['label' => EducationInventoryModule::t('modules/eduinventory/app', 'Teachers'), 'url' => ['/eduinventory/teacher']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="teachers-import col-lg-6">
	<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
		<?= $form->field($import_model, 'importfile')->fileInput()->label(EducationInventoryModule::t('modules/eduinventory/app', 'Select Teachers Excel File')) ?>
	    <div class="form-group  text-right">
        	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton(EducationInventoryModule::t('modules/eduinventory/app', 'Import Teachers'), ['class' => 'btn btn-primary']) ?>
    	</div>
	<?php ActiveForm::end(); ?>
</div>