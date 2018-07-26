<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$this->title = Yii::t('substituteteacher', 'Update placement decision');

$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->label, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('substituteteacher', 'Update');
?>
<div class="placement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
