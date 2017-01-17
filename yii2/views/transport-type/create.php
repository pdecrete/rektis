<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TransportType */

$this->title = Yii::t('app', 'Create Transport Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transport Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
