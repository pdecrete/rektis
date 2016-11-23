<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TransportStatus */

$this->title = Yii::t('app', 'Create Transport Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transport Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transport-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
