<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaewithdrawal */
/* @var $form yii\widgets\ActiveForm */
?>

<?= 
    $this->render('/default/kaeinfo', [
        'model' => $model,
        'kae' => $kae,
        'kaeCredit' => $kaeCredit,
        'kaeCreditSumPercentage' => $kaeCreditSumPercentage,
        'kaeWithdrwals' => $kaeWithdrwals
    ]) 
?>
    
<div class="finance-kaewithdrawal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kaewithdr_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kaewithdr_decision')->textInput(['maxlength' => true]) ?>

    <?php ;// $form->field($model, 'kaewithdr_date')->textInput(['value' => date("Y-m-d H:i:s")]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('modules/finance/app', 'Create') : Module::t('modules/finance/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
