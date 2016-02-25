<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AuthRule */

$this->title = 'Create Auth Rule';
$this->params['breadcrumbs'][] = ['label' => 'Auth Rules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-rule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
