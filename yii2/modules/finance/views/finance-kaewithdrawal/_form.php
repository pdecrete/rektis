<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaewithdrawal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="finance-kaewithdrawal-form col-lg-6">
    
        <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
    
        <?= $form->field($model, 'kaewithdr_amount')->textInput(['maxlength' => true,
                                                        'type' => 'number',
                                                        'min' => "0.00" ,
                                                        'step' => '0.01',
                                                        'style' => 'text-align: left',
                                                        'value' => $model['kaewithdr_amount']])->label(false); ?>
    
        <?= $form->field($model, 'kaewithdr_decision')->textInput(['maxlength' => true]) ?>
    	
    	<?php ;//$form->field($model, 'decisionfile')->fileInput() ?>
    	
        <?php ;// $form->field($model, 'kaewithdr_date')->textInput(['value' => date("Y-m-d H:i:s")])?>
    
        <div class="form-group pull-right">
        	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton($model->isNewRecord ? Module::t('modules/finance/app', 'Create') : Module::t('modules/finance/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>        	
        </div>
    
        <?php ActiveForm::end(); ?>    
    </div>
    <div class="col-lg-6">
        <?=
        $this->render('/default/kaeinfo', [
            'model' => $model,
            'kae' => $kae,
            'kaeCredit' => $kaeCredit,
            'kaeCreditSumPercentage' => $kaeCreditSumPercentage,
            'kaeWithdrwals' => $kaeWithdrwals,
            'options' => ['showbutton' => 0, 'collapsed' => 1]
        ])
        ?>
    </div>
</div>