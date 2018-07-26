<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\SubstituteTeacher\models\Placement */

$this->title = Yii::t('substituteteacher', 'Create Placement Decision');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Placements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="placement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
