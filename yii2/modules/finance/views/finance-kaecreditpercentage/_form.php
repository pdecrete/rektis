<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\finance\Module;
use app\modules\finance\components\Money;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditpercentage */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>"; print_r($kae); echo "</pre>"; die();

//$kaecredit->kaecredit_amount = Money::toCurrency($kaecredit->kaecredit_amount, true);
$model->kaeperc_percentage = Money::toPercentage($model->kaeperc_percentage);

$model->kaeperc_date = date("Y-m-d H:i:s");
$kae->kae_id = sprintf('%04d', $kae->kae_id);
?>
<div class="row">
<div class="finance-kaecreditpercentage-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kaeperc_percentage')->textInput((['maxlength' => true])) ?>  

    <?= $form->field($model, 'kaeperc_decision')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'kaeperc_date')->textInput(['readonly' => true]) ?>

    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Module::t('modules/finance/app', 'Create') : Module::t('modules/finance/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>    	
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="col-lg-6">
    <div class="container-fluid well">
  		<div class="row">
            <table class="table table-hover">
                <thead><tr><th class="text-center" colspan="2"><?php echo Module::t('modules/finance/app', 'RCN') . ' ' . sprintf('%04d', $kae->kae_id) . " - " . $kae->kae_title  ?></th></tr></thead>
                <tr class="info"><td><?= Module::t('modules/finance/app', 'RCN Initial Credit') ?>:</td><td class="text-right"><?= Money::toCurrency($kaecredit->kaecredit_amount, true) ?></td></tr>
                <tr class="info"><td><?= Module::t('modules/finance/app', 'Total Attributed Percentage') ?>:</td><td class="text-right"><?= Money::toPercentage($kaecredit_sumpercentage) ?></td></tr>
            </table>
		</div>
	</div>
</div>
</div>
