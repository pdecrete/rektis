<?php

use app\modules\finance\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceSupplier */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Supplier',
]) . $model->suppl_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->suppl_id, 'url' => ['view', 'id' => $model->suppl_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-supplier-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
