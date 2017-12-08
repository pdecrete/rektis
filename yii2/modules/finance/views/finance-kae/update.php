<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKae */
$this->title = Module::t('modules/finance/app', 'Update RCN');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'RCN'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->kae_id, 'url' => ['view', 'id' => $model->kae_id]];
$this->params['breadcrumbs'][] = Module::t('modules/finance/app', 'Update');
?>
<div class="finance-kae-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'readonly' => true,
    ]) ?>

</div>
