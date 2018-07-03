<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\PlacementPrint */

$this->title = Yii::t('substituteteacher', 'Update placement print');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placement Prints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->placementTeacher->teacherBoard->teacher->name . ' ' . $model->placementTeacher->teacherBoard->label, 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="placement-print-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
