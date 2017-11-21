<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\OperationSpecialisation */

$this->title = Yii::t('substituteteacher', 'Create Operation Specialisation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Operation Specialisations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-specialisation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
