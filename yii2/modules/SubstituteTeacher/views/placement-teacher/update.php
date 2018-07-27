<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$this->title = Yii::t('substituteteacher', 'Update placement');

$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placement decisions'), 'url' => ['placement/index']];
$this->params['breadcrumbs'][] = ['label' => $model->placement->label, 'url' => ['placement/view', 'id' => $model->placement_id]];
$this->params['breadcrumbs'][] = ['label' => $model->teacherBoard->label, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="placement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsPlacementPositions' => $modelsPlacementPositions
    ]) ?>

</div>
