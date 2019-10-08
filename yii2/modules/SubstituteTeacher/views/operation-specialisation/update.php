<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\OperationSpecialisation */

$this->title = Yii::t('substituteteacher', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Operation Specialisations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="operation-specialisation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
