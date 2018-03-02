<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\finance\models\FinanceTaxoffice;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceSupplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-supplier-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'suppl_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_vat')->textInput()->widget(
    \yii\widgets\MaskedInput::className(),
                                                    ['mask' => '999999999']
)?>

    <?= $form->field($model, 'suppl_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_phone')->widget(

                                                        \yii\widgets\MaskedInput::className(),
                                                    ['mask' => '9999999999']

                                                    )?>

    <?= $form->field($model, 'suppl_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suppl_fax')->widget(

                                                        \yii\widgets\MaskedInput::className(),
                                                    ['mask' => '9999999999']

                                                    )?>

    <?= $form->field($model, 'suppl_iban')->widget(

                                                        \yii\widgets\MaskedInput::className(),
                                                    ['mask' => 'GR9999999999999999999999999']

                                                    )?>

    <?= $form->field($model, 'suppl_employerid')->textInput(['maxlength' => true]) ?>

    
    <?= $form->field($model, 'taxoffice_id')->dropDownList(
        ArrayHelper::map(FinanceTaxoffice::find()->all(), 'taxoffice_id', 'taxoffice_name'),
            ['prompt'=>'ΔΟΥ Προμηθευτή']

                                                    )
    ?>

    <div class="form-group text-right">
		<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>    
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>    	
    </div>

    <?php ActiveForm::end(); ?>

</div>
