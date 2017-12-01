<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditpercentage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-kaecreditpercentage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kaeperc_percentage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kaeperc_date')->textInput() ?>

    <?= $form->field($model, 'kaeperc_decision')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kaecredit_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
