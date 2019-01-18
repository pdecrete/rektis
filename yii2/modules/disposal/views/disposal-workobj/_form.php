<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\disposal\models\DisposalWorkobj */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="disposal-workobj-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'disposalworkobj_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disposalworkobj_description')->textInput(['maxlength' => true]) ?>

    <div class="form-group text-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
