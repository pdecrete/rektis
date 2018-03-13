<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaewithdrawal */

$this->title = Module::t('modules/finance/app', 'Update Withdrawal');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Withdrawals from RCN Credits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kaewithdrawal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'kae' => $kae,
        'kaeCredit' => $kaeCredit,
        'kaeCreditSumPercentage' => $kaeCreditSumPercentage,
        'kaeWithdrwals' => $kaeWithdrwals,
        'updateFlag' => 1
    ]) ?>

</div>
