<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admapp\Util\Html as admappHtml;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-form">
    <?php
    $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-sm-6\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>
    <?= admappHtml::displayValueOfField($model, 'username', 2, 6) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?php // $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            Συμπληρώστε τον κωδικό πρόσβασης <strong>μόνο εάν θέλετε να 
                αλλάξετε τον κωδικό πρόσβασης</strong>.
        </div>
    </div>
    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'new_password_repeat')->passwordInput(['maxlength' => true]) ?>

    <?= admappHtml::displayValueOfField($model, 'roles', 2, 6) ?>
    <?= admappHtml::displayValueOfField($model, ['status', 'statuslabel'], 2, 6) ?>
    <?= admappHtml::displayValueOfField($model, 'last_login', 2, 6) ?>
    <?= admappHtml::displayValueOfField($model, 'create_ts', 2, 6) ?>
    <?= admappHtml::displayValueOfField($model, 'update_ts', 2, 6) ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton('Ενημέρωση στοιχείων', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Επιστροφή', ['account'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
