<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalReason */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="disposal-reason-form col-lg-9">
    
        <?php $form = ActiveForm::begin(); ?>
    
        <?= $form->field($model, 'disposalreason_name')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'disposalreason_description')->textInput(['maxlength' => true]) ?>
    
        <div class="form-group pull-right">
        	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>        
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>    
    </div>
    <div class="alert alert-warning col-lg-3">  
  		<strong>Προσοχή!</strong> Εισάγετε το λεκτικό του λόγου διάθεσης στην αιτιατική πτώση καθώς σε αυτή την πτώση θα εμφανίζεται στα παραγώμενα έγγραφα π.χ. "Λόγους υγείας και όχι "Λόγοι υγείας".
    </div>
</div>