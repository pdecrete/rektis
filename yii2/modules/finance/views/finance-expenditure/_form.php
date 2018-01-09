<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceExpenditure */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="finance-expenditure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'exp_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_id')->textInput() ?>
    
    <?= $form->field($model, 'fpa_value')->dropDownList(
        ArrayHelper::map($vat_levels,'fpa_value', 'fpa_value'),
        ['prompt'=> Module::t('modules/finance/app', 'VAT')])
    ?>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
