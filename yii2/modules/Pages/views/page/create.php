<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = 'Νέα σελίδα';
$this->params['breadcrumbs'][] = ['label' => 'Σελίδες', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-create">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>