<?php

use app\modules\finance\Module;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaewithdrawal */

$this->title = Module::t('modules/finance/app', 'New Withdrawal');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'RCN Withdrawal'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-kaewithdrawal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
