<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
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
    <?php if (!$model->isNewRecord) : ?>
        <?= admappHtml::displayValueOfField($model, 'id', 2, 6) ?>
    <?php endif; ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?php if (!$model->isNewRecord) : ?>
        <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropdownList(User::getStatusLabelsArray()) ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            Συμπληρώστε τον κωδικό πρόσβασης <strong>μόνο εάν θέλετε να 
                αλλάξετε τον κωδικό πρόσβασης</strong>.
        </div>
    </div>
    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'new_password_repeat')->passwordInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'activeroles')->checkboxList(
            array_combine(array_keys(Yii::$app->authManager->getRoles()), array_keys(Yii::$app->authManager->getRoles()))
    )
    ?>
    <?php if (!$model->isNewRecord) : ?>
        <?= $form->field($model, 'last_login')->textInput() ?>
        <?= admappHtml::displayValueOfField($model, 'create_ts', 2, 6) ?>
        <?= admappHtml::displayValueOfField($model, 'update_ts', 2, 6) ?>
    <?php endif; ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? 'Δημιουργία' : 'Ενημέρωση στοιχείων', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Επιστροφή', ['account'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
