<?php

use app\modules\finance\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="finance-kae-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kae_id') ?>

    <?= $form->field($model, 'kae_title') ?>

    <?= $form->field($model, 'kae_description') ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('modules/finance/app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
