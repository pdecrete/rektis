<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditpercentage */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Kaecreditpercentage',
]) . $model->kaeperc_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Kaecreditpercentages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kaeperc_id, 'url' => ['view', 'id' => $model->kaeperc_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="finance-kaecreditpercentage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
