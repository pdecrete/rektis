<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransportFundsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-funds-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php //echo $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'ada') ?>

    <?= $form->field($model, 'service') ?>

    <?= $form->field($model, 'code') ?>

	<?= $form->field($model, 'kae')->widget(Select2::classname(), [
		'data' => \app\models\TransportFunds::kaeList(),
          'options' => ['placeholder' => Yii::t('app', 'Choose...')],
          'pluginOptions' => [
              'allowClear' => true
          ],
        ]);
        ?>
    <?php //echo $form->field($model, 'amount') ?>

    <?php //echo $form->field($model, 'count_flag') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
