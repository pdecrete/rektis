<?php

use app\modules\finance\Module;
use app\modules\finance\components\Integrity;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\finance\models\FinanceYear */
$this->title = $model->year;
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Expenditures Management'), 'url' => ['/finance/default']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Financial Year Administration'), 'url' => ['/finance/default/administeryear']];
$this->params['breadcrumbs'][] = ['label' => Module::t('modules/finance/app', 'Finance Years'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $this->render('/default/infopanel'); ?>
<div class="finance-year-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Module::t('modules/finance/app', 'Update'), ['update', 'id' => $model->year], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Module::t('modules/finance/app', 'Delete'), ['delete', 'id' => $model->year], [
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
            'year',
            'year_credit',
            [   'attribute' => 'year_lock',
                'format' => 'html',
                'value' => function ($dataProvider) {return $dataProvider->year_lock == 1 ? Module::t('modules/finance/app', 'Yes') : Module::t('modules/finance/app', 'No');}
            ],
            [   'attribute' => 'year_iscurrent',
                'format' => 'html',
                'value' => function ($dataProvider) {return $dataProvider->year_iscurrent == 1 ? Module::t('modules/finance/app', 'Yes') : Module::t('modules/finance/app', 'No');}
            ]
        ],
    ]) ?>

</div>
