<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('substituteteacher', 'Manage call positions distribution');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'PYSEEP positions distribution'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="call-position-create">

    <h1><?= $callModel->label ?> <?= Html::a(Html::icon('eye-open'), ['call/view', 'id' => $callModel->id], ['class' => 'btn btn-default']) ?> <small><?= Html::encode($this->title) ?></small></h1>

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
