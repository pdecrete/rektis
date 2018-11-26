<?php

use app\modules\disposal\DisposalModule;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalLocaldirdecision */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disposal-localdirdecision-form">
	<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-lg-4">
			<?= $form->field($model, 'directorate_id')->widget(Select2::classname(), [
                         'data' => ArrayHelper::map($directorates, 'directorate_id', 'directorate_name'),
                         'options' => ['placeholder' => DisposalModule::t('modules/disposal/app', 'Select Directorate ...')],
                    ])->label('Διεύθυνση Εκπαίδευσης'); ?>
		</div>	
		<div class="col-lg-4"><?= $form->field($model, 'localdirdecision_protocol')->textInput(['maxlength' => true]) ?></div>
		<div class="col-lg-4"><?= $form->field($model, 'localdirdecision_action')->textInput(['maxlength' => true]) ?></div>
	</div>
	<div class="row">
		<div class="col-lg-12"><?= $form->field($model, 'localdirdecision_subject')->textInput(['maxlength' => true]) ?></div>
	</div>
    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? DisposalModule::t('modules/disposal/app', 'Create') : DisposalModule::t('modules/disposal/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
