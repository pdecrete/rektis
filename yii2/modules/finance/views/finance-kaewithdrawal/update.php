<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaewithdrawal */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Kaewithdrawal',
]) . $model->kaewithdr_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaewithdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kaewithdr_id, 'url' => ['view', 'id' => $model->kaewithdr_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-kaewithdrawal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
