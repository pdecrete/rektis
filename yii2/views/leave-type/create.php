<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LeaveType */

$this->title = Yii::t('app', 'Create Leave Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leave Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
