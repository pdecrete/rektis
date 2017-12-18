<?php

use app\modules\finance\Module;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceTaxoffice */

$this->title = Yii::t('app', 'Create Finance Taxoffice');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Parameters'), 'url' => ['/finance/default/parameterize']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Taxoffices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-taxoffice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
