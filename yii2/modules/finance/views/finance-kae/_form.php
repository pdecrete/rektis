<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKae */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="finance-kae-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kae_id')->textInput(['readonly' => $readonly]) ?>
	
    <?= $form->field($model, 'kae_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kae_description')->textInput(['maxlength' => true]) ?>

    <div class="form-group pull-right">
        <?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Module::t('modules/finance/app', 'Create') :  Module::t('modules/finance/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>    	
    </div>

    <?php ActiveForm::end(); ?>

</div>
