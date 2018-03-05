<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecreditpercentage */

$this->title = Module::t('modules/finance/app', 'Update RCN Percentage Attribution');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'RCN Credits Percentages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Module::t('modules/finance/app', 'Update');


?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-kaecreditpercentage-update">

    <h1><?= Html::encode($this->title) ?></h1>
 
    <?= $this->render('_form', [
        'model' => $model,
        'kae' => $kae,
        'kaecredit' => $kaecredit,
        'kaecredit_sumpercentage' => $kaecredit_sumpercentage
    ]) ?>

</div>
