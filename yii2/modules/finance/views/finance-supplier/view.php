<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceSupplier */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->title = $model->suppl_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/default/infopanel'); ?>
<div class="finance-supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->suppl_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->suppl_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'suppl_id',
            'suppl_name',
            'suppl_vat',
            'suppl_address',
            'suppl_phone',
            'suppl_fax',
            'suppl_iban',
            'suppl_employerid',
            'taxoffice_id',
        ],
    ]) ?>

</div>
