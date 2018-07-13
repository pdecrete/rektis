<?php

use app\modules\schooltransport\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\schooltransport\models\SchtransportStateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Module::t('modules/schooltransport/app', 'School Transportations'), 'url' => ['/schooltransport/default']];
$this->title = Module::t('modules/schooltransport/app', 'Transportation Approval States');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schtransport-state-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p class="text-right">
        <?= Html::a(Yii::t('app', 'Create Schtransport State'), ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'state_id',
             'label' => 'A/A',
             'headerOptions' => ['style' => 'max-width: 100px']
            ],
            'state_name',
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{update}'
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
