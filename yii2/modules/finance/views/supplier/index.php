<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\finance\models\SupplierQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'suppl_id',
            'suppl_name',
            'suppl_vat',
            'suppl_address',
            'suppl_phone',
            // 'suppl_fax',
            // 'suppl_iban',
            // 'suppl_employerid',
            // 'suppl_taxoffice',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
