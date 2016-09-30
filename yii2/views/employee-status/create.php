<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EmployeeStatus */

$this->title = Yii::t('app', 'Create Employee Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
