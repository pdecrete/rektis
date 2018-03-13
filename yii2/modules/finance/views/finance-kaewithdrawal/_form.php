<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaewithdrawal */
/* @var $form yii\widgets\ActiveForm */
$existingFileUrl = "";
if($updateFlag && !is_null($model->kaewithdr_decisionfile)){
    $existingFile = Url::to(['/finance/finance-kaewithdrawal/download', 'id' =>$model['kaewithdr_id']]);
    $existingFileUrl = ' (<i>' . Html::a(Module::t('modules/finance/app', 'Download Decision') . '&nbsp;<span class="glyphicon glyphicon-download"></span>', $existingFile,
        ['title' => Module::t('modules/finance/app', 'Download Decision'),
            'data-method' => 'post',
            'target' => '_blank'
        ]). '</i>)';
}
?>

<div class="row">
    <div class="finance-kaewithdrawal-form col-lg-6">
    
        <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
    
        <?= $form->field($model, 'kaewithdr_amount')->textInput(['maxlength' => true,
                                                        'type' => 'number',
                                                        //'min' => "0.00" ,
                                                        'step' => '0.01',
                                                        'style' => 'text-align: left',
                                                        'value' => $model['kaewithdr_amount']])->label(false); ?>
    
        <?= $form->field($model, 'kaewithdr_decision')->textInput(['maxlength' => true]) ?>
    	
    	<?= $form->field($model, 'decisionfile')->fileInput()->label(Module::t('modules/finance/app', 'Decision File') . $existingFileUrl) ?>
    	
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