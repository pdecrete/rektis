<?php 

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use app\modules\SubstituteTeacher\models\Call;

// @param array $route
// @param int $call_id
?>
<div class="well">
    <?php 
    $form = ActiveForm::begin([
            'id' => 'call-choose-form',
            'method' => 'GET',
            'action' => $route,
            'options' => ['class' => 'form-horizontal'],
            'enableClientValidation' => false,
        ]);
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <?= Yii::t('substituteteacher', 'Calls') ?>
            </div>
            <div class="col-sm-6">
                <?= Html::dropDownList('call_id', $call_id, Call::defaultSelectables(), ['class' => 'form-control', 'prompt' => Yii::t('substituteteacher', 'Choose...')]) ?>
            </div>
            <div class="col-sm-3">
                <?=
                    Html::submitButton(Yii::t('substituteteacher', 'Choose call'), [
                        'class' => 'btn btn-primary',
                    ])
                    ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>