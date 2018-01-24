<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Teacher */

$this->title = Yii::t('substituteteacher', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="teacher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsPlacementPreferences' => $modelsPlacementPreferences
    ]) ?>

</div>
