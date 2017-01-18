<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LeaveBalance */

$this->title = Yii::t('app', 'Update Leave Balance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Balances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="leave-balance-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
