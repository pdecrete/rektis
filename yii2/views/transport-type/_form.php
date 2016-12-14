<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admapp\Util\Html as admappHtml;

/* @var $this yii\web\View */
/* @var $model app\models\TransportType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transport-type-form">

    <?php 
        $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php
    $availabletemplatefilenames = $model->availabletemplatefilenames;
    $atf = array_combine($availabletemplatefilenames, $availabletemplatefilenames);
    ?>
    <?= $form->field($model, 'templatefilename1')->dropDownList($atf, ['prompt' => Yii::t('app', 'Select a template file')]); ?>
	<?=	$form->field($model, 'templatefilename2')->dropDownList($atf, ['prompt' => Yii::t('app', 'Select a template file')]); ?>
	<?=	$form->field($model, 'templatefilename3')->dropDownList($atf, ['prompt' => Yii::t('app', 'Select a template file')]); ?>
    <?=	$form->field($model, 'templatefilename4')->dropDownList($atf, ['prompt' => Yii::t('app', 'Select a template file')]); ?>
    <?php if (!$model->isNewRecord) : ?>
        <?= admappHtml::displayValueOfField($model, 'create_ts', 2, 6) ?>
        <?= admappHtml::displayValueOfField($model, 'update_ts', 2, 6) ?>
    <?php endif; ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
