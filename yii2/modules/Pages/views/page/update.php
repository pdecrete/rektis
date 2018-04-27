<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = 'Ενημέρωση σελίδας';
$this->params['breadcrumbs'][] = ['label' => 'Σελίδες', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identity, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Ενημέρωση';

?>
<div class="page-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
