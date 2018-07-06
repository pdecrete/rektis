<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$this->title = Yii::t('substituteteacher', 'Teacher Placement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placements'), 'url' => ['placement/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsPlacementPositions' => $modelsPlacementPositions,
        'placement_id' => $placement_id
    ]) ?>

</div>
