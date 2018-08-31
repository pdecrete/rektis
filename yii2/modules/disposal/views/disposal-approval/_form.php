<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalApproval */
/* @var $form yii\widgets\ActiveForm */

//echo "<pre>"; print_r($disposals_models); echo "</pre>"; die();
?>

<div class="disposal-approval-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'approval_regionaldirectprotocol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approval_localdirectprotocol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approval_notes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approval_file')->textInput(['maxlength' => true]) ?>
    
    <?php foreach($disposals_models as $index=>$disposal_model): ?>
		<?php echo $disposal_model['disposal_id'];  //$form->field($disposalapproval_models[$index], 'disposal_id')->checkbox(); ?>
    <?php endforeach;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>