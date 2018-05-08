<?php

use app\modules\schooltransport\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\schooltransport\models\Schoolunit */
/* @var $form yii\widgets\ActiveForm */

//echo "<pre>"; print_r($directorates); "</pre>"; die();
?>

<div class="schoolunit-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'school_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'directorate_id')->dropDownList(
                ArrayHelper::map($directorates, 'directorate_id', 'directorate_name'),
                ['prompt' => Module::t('modules/schooltransport/app', 'Directorate of Education')]);
    ?>
    

    <div class="form-group pull-right">
		<?= Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default']) ?>    
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
