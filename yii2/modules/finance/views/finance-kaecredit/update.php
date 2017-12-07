<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceKaecredit */

$this->title = Module::t('modules/finance/app', 'Update RCΝ credits');
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'RCN Credits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Module::t('modules/finance/app', 'Update');

?>
<div class="finance-kaecredit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_editform', ['model' => $model, 'kaetitles' => $kaetitles]); ?>

</div>