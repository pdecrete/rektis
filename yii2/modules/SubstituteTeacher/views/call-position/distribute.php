<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('substituteteacher', 'Manage call positions distribution');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Call Positions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="call-position-create">

    <h1><?= $callModel->label ?> <small><?= Html::encode($this->title) ?></small></h1>

    <?=
    $this->render('_distribution_form', [
        'callModel' => $callModel,
        'positionsSearchModel' => $positionsSearchModel,
        'positionsDataProvider' => $positionsDataProvider,
        'callPositionsSearchModel' => $callPositionsSearchModel,
        'callPositionsDataProvider' => $callPositionsDataProvider,
    ])

    ?>

</div>
