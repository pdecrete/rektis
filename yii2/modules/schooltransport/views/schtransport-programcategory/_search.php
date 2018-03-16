<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\SchtransportProgramcategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schtransport-programcategory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'programcategory_id') ?>

    <?= $form->field($model, 'programcategory_actioncode') ?>

    <?= $form->field($model, 'programcategory_actiontitle') ?>

    <?= $form->field($model, 'programcategory_actionsubcateg') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
