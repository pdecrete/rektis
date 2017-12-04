<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditpercentage */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($kae); echo "</pre>"; die();
$kaecredit->kaecredit_amount = Money::toCurrency($kaecredit->kaecredit_amount);
$model->kaeperc_percentage = Money::toPercentage($model->kaeperc_percentage);
?>

<div class="finance-kaecreditpercentage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($kae, 'kae_id')->textInput(['readonly' => true]) ?>
    <?= $form->field($kae, 'kae_title')->textInput(['readonly' => true]) ?>
    <?= $form->field($kaecredit, 'kaecredit_amount')->textInput(['readonly' => true]) ?>
	
    <?= $form->field($model, 'kaeperc_percentage')->textInput((['maxlength' => true,] )) ?>

    <?= $form->field($model, 'kaeperc_date')->textInput() ?>

    <?= $form->field($model, 'kaeperc_decision')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
