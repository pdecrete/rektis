<?php

use app\modules\finance\Module;
use app\modules\finance\components\Money;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceFpa */
/* @var $form yii\widgets\ActiveForm */
$model->fpa_value = Money::toPercentage($model->fpa_value, false);
?>

<div class="finance-fpa-form col-lg-3">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fpa_value')->textInput() ?>

    <div class="form-group pull-right">
    	<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Module::t('modules/finance/app', 'Create') : Module::t('modules/finance/app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>