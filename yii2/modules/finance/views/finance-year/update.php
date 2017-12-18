<?php

use app\modules\finance\Module;
use app\modules\finance\components\Integrity;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceYear */
$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => 'Finance Year',]) . $model->year;
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Finance Years'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->year, 'url' => ['view', 'id' => $model->year]];
$this->params['breadcrumbs'][] = Module::t('modules/finance/app', 'Update');
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-year-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
